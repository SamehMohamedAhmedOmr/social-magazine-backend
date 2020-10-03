<?php

namespace Modules\WareHouse\Services\CMS;

use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\Notifications\Jobs\ProductSubscriptionNotifications;
use Modules\WareHouse\Facades\StockHelper;
use Modules\WareHouse\Imports\StockImport;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;
use Modules\WareHouse\Repositories\StockRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Services\CMS\Stock\StockCommonService;


class StockImportService extends LaravelServiceClass
{
    private $warehouse_repo;
    private $stock_repo;
    private $product_warehouse_repo;
    private $product_repo;
    private $stockCommonService;

    private $stock_type_added = 0;

    private $success_importing_msg = 'success';
    private $failed_importing_msg = 'Quantity Requested is less than the stock quantity';

    public function __construct(
        WarehouseRepository $warehouse_repo,
        StockRepository $stock_repo,
        ProductWarehouseRepository $product_warehouse_repo,
        ProductRepository $product_repo,
        StockCommonService $stockCommonService
    )
    {
        $this->warehouse_repo = $warehouse_repo;
        $this->stock_repo = $stock_repo;
        $this->product_warehouse_repo = $product_warehouse_repo;
        $this->product_repo = $product_repo;
        $this->stockCommonService = $stockCommonService;
    }


    /* Uploading Functionality */
    /**
     * Handles upload stock sheet
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadStockSheet()
    {
        $sku_array = collect([]);
        $qty_array = collect([]);
        $excel_data = collect([]);
        $file_path = '';

        $stock_sheet_file = request('stock_sheet');

        $acceptable_extension = collect(['xlsx', 'xls']);
        $file_extension = $stock_sheet_file->getClientOriginalExtension();

        if (!$acceptable_extension->contains($file_extension)) {
            throw  ValidationException::withMessages([
                'stock_sheet' => ['File should only with type Excel'],
            ]);
        }

        $file_data = Excel::toArray(new StockImport, $stock_sheet_file);

        if (count($file_data) > 0) {
            $first_tab = $file_data[0];
            if (count($first_tab) > 0) {

                // extract SKU
                $sku_index = 0;
                foreach ($first_tab as $key => $data) {
                    if ($key == 0) { // Excel Header Should be in the First Row
                        $first_column = $this->checkHeader($data);
                        $sku_index = strtolower($first_column) === 'sku' ? 0 : 1;
                    } else {
                        list($sku, $qty) = $this->getSkuAndQty($data, $sku_index);
                        $sku_array->push($sku);
                        $qty_array->push($qty);
                    }
                }
                // Get all products that has sku
                $products = $this->product_repo->getBulk('sku', $sku_array);


                foreach ($sku_array as $key => $singleSku) {
                    $product_id = null;
                    $product_name = null;
                    $is_exists = 0;
                    $product = $products->where('sku', $singleSku)->first();

                    if (isset($product)) {
                        $product_id = $product->id;
                        $product_name = ($product->languages[0]) ? $product->languages[0]->name : null;
                        $is_exists = 1;
                    }

                    $excel_row = collect([
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'stock_keeping_unit' => $singleSku,
                        'quantity' => $qty_array[$key],
                        'is_exists' => $is_exists
                    ]);
                    $excel_data->push($excel_row);
                }
                $file_name = request()->file('stock_sheet')->getClientOriginalName();
                request()->file('stock_sheet')->storeAs('public/stocks/import/', $file_name);

                $file_path = 'public/stocks/import/' . $file_name;
            } else {
                throw  ValidationException::withMessages([
                    'stock_sheet' => ['There\'s no Data in the file'],
                ]);
            }
        } else {
            throw  ValidationException::withMessages([
                'stock_sheet' => ['There\'s no Data in the file'],
            ]);
        }


        return ApiResponse::format(200, ['excel_data' => $excel_data, 'file_path' => $file_path]);
    }


    /**
     * Handles Header Names
     *
     * @param $data
     * @return string
     * @throws Exception
     */
    public function checkHeader($data)
    {
        $headers = collect(['SKU', 'QTY', 'qty', 'sku']);
        $first_column = $data[0];
        foreach ($data as $table_header) {
            if ($table_header == null) {
                continue;
            }
            $flag = ($headers->contains($table_header)) ? true : false;
            if ($flag == false) {
                throw  ValidationException::withMessages([
                    'stock_sheet' => ['Excel Headers should be SKU AND QTY'],
                ]);
            }
        }
        return $first_column;
    }


    public function getSkuAndQty($data, $sku_index)
    {
        $sku = ($sku_index == 0) ? $data[0] : $data[1];
        $qty = ($sku_index == 0) ? $data[1] : $data[0];

        return [$sku, $qty];
    }


    /* Storing Functionality */
    /**
     * store into Stock and Product Warehouse
     *
     * @throws Exception
     */
    public function store()
    {
        try {
            DB::beginTransaction();

            $to_warehouse = request('to_warehouse');
            $from_warehouse = request('from_warehouse');
            $file_path = request('file_path');

            list($products, $quantities, $is_sell_with_availabilities) = $this->extractProductQuantity();

            if (request('type') == $this->stock_type_added) {
                list($stock_data, $products_list) = $this->addingToWarehouse($products, $quantities, $to_warehouse, $file_path, $is_sell_with_availabilities);
            } else { // type moved
                list($stock_data, $products_list) = $this->movingFromWarehouse($products, $quantities, $to_warehouse, $from_warehouse, $file_path, $is_sell_with_availabilities);
            }

            // Store Stock Data
            $this->stock_repo->createMany($stock_data);

            DB::commit();
            return ApiResponse::format(200, $products_list);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }


    public function extractProductQuantity()
    {
        $products = collect(request('product'));

        $products_id = $products->pluck('product_id');

        $quantities = $products->pluck('qty');

        $is_sell_with_availability = $products->pluck('available');

        return [$products_id, $quantities, $is_sell_with_availability];
    }


    public function addingToWarehouse($products, $quantities, $to_warehouse, $file_path, $is_sell_with_availabilities)
    {

        $stock_data = [];
        $product_list = collect([]); // used to Handle returning object
        $to_warehouse_product = [];

        // Get all products list that require to increase in ($to_warehouse)
        $products_quantity = $this->product_warehouse_repo->getBulk($products, $to_warehouse);

        foreach ($products as $index => $product) {
            // get the record of product in product quantity table
            $product_qty = $products_quantity->where('product_id', $product)->first();

            // calculate new quantity and old quantity
            list($new_quantity, $old_quantity) = $this->stockCommonService->newQuantity($product_qty, $quantities[$index]);

            $to_warehouse_product [] = $this->stockCommonService->prepareProductsQuantity($product, $new_quantity, $to_warehouse,
                isset($is_sell_with_availabilities[$index]) ? $is_sell_with_availabilities[$index] : 0);

            $stock_data [] = $this->stockCommonService->prepareStockData(
                $product,
                $quantities[$index],
                $to_warehouse,
                null,
                $file_path,
                request('type')
            );

            $product_list->push($this->stockCommonService->prepareProductList(
                $product,
                $to_warehouse,
                $new_quantity,
                $old_quantity,
                true,
                $this->success_importing_msg
            ));
        }

        $target_warehouse = $this->warehouse_repo->get($to_warehouse);

        // detach the Product from the warehouse
        // attach the $to_warehouse_product from the warehouse
        $this->warehouse_repo->detachProductWarehouse($target_warehouse, $products);

        $this->warehouse_repo->attachProductWarehouse($target_warehouse, $to_warehouse_product);

        return [$stock_data, $product_list];
    }


    public function movingFromWarehouse($products, $quantities, $to_warehouse, $from_warehouse, $file_path, $is_sell_with_availabilities)
    {
        $product_list = collect([]); // used to Handle returning object
        $stock_data = [];
        $acceptable_products = [];

        $to_warehouse_product = [];
        $from_warehouse_product = [];

        $products_quantity_to = $this->product_warehouse_repo->getBulk($products, $to_warehouse);
        $products_quantity_from = $this->product_warehouse_repo->getBulk($products, $from_warehouse);

        foreach ($products as $index => $product) {
            // Moving to Warehouse
            $product_qty_from = $products_quantity_from->where('product_id', $product)->first();
            if (isset($product_qty_from)) {
                if ($product_qty_from->projected_quantity < $quantities[$index]) {
                    $product_list->push($this->stockCommonService->prepareProductList(
                        $product,
                        $from_warehouse,
                        $product_qty_from->projected_quantity,
                        null,
                        false,
                        $this->failed_importing_msg
                    ));
                } else {
                    $acceptable_products [] = $product;
                    // moving Process
                    list($new_quantity_from, $old_quantity_from) = $this->stockCommonService->newQuantity($product_qty_from, $quantities[$index], 'MOVED');

                    $from_warehouse_product [] = $this->stockCommonService->prepareProductsQuantity($product, $new_quantity_from, $from_warehouse,
                        isset($is_sell_with_availabilities[$index]) ? $is_sell_with_availabilities[$index] : 0);

                    // Adding product to (to_warehouse)
                    $product_qty = $products_quantity_to->where('product_id', $product)->first();
                    list($new_quantity_to, $old_quantity_to) = $this->stockCommonService->newQuantity($product_qty, $quantities[$index]);

                    $to_warehouse_product [] = $this->stockCommonService->prepareProductsQuantity($product, $new_quantity_to, $to_warehouse,
                        isset($is_sell_with_availabilities[$index]) ? $is_sell_with_availabilities[$index] : 0);

                    $stock_data [] = $this->stockCommonService->prepareStockData(
                        $product,
                        $quantities[$index],
                        $to_warehouse,
                        $from_warehouse,
                        $file_path,
                        request('type')
                    );

                    // Product To
                    $product_list->push($this->stockCommonService->prepareProductList(
                        $product,
                        $to_warehouse,
                        $new_quantity_to,
                        $old_quantity_to,
                        true,
                        $this->success_importing_msg
                    ));
                }
            } else { // there's no product warehouse
                $product_list->push($this->stockCommonService->prepareProductList(
                    $product,
                    $from_warehouse,
                    0,
                    null,
                    false,
                    $this->failed_importing_msg
                ));
            }
        }

        // detach the Product from the warehouse
        // attach the $to_warehouse_product from the warehouse
        $target_warehouse_to = $this->warehouse_repo->get($to_warehouse);
        $this->warehouse_repo->detachProductWarehouse($target_warehouse_to, $acceptable_products);
        $this->warehouse_repo->attachProductWarehouse($target_warehouse_to, $to_warehouse_product);

        // detach the Product from the warehouse
        // attach the $from_warehouse_product from the warehouse
        $target_warehouse_from = $this->warehouse_repo->get($from_warehouse);
        $this->warehouse_repo->detachProductWarehouse($target_warehouse_from, $acceptable_products);
        $this->warehouse_repo->attachProductWarehouse($target_warehouse_from, $from_warehouse_product);


        return [$stock_data, $product_list];
    }

    public function ProductNotifications($request){
        $notifications = collect([]);

        $notifications = StockHelper::addProductNotification($notifications, $request->products, $this->product_repo);

        \Session::put('product_notifications', $notifications);

        \Session::put('to_warehouse', $request->warehouse_id);

        ProductSubscriptionNotifications::dispatchAfterResponse();

        return ApiResponse::format(200, null,'Subscriber users are successfully notified');
    }
}
