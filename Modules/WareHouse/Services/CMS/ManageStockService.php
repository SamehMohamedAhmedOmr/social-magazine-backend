<?php


namespace Modules\WareHouse\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\Notifications\Jobs\ProductSubscriptionNotifications;
use Modules\WareHouse\Facades\StockHelper;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;
use Modules\WareHouse\Repositories\StockRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Services\CMS\Stock\StockCommonService;
use Modules\WareHouse\Transformers\CMS\Stock\ProductStockResource;
use Modules\WareHouse\Transformers\CMS\Stock\ProductWarehouseResource;
use Modules\WareHouse\Transformers\StockResource;


class ManageStockService extends LaravelServiceClass
{
    protected $product_warehouse_repository;
    protected $stock_repository;
    protected $product_repository;
    protected $stockCommonService;
    protected $stock_repo;
    protected $warehouse_repo;
    protected $add_type = 0;
    protected $moved_type = 1;

    public function __construct(ProductWarehouseRepository $product_warehouse_repository,
                                StockRepository $stock_repository,
                                WarehouseRepository $warehouse_repo,
                                StockCommonService $stockCommonService,
                                StockRepository $stock_repo,
                                ProductRepository $product_repository)
    {
        $this->product_warehouse_repository = $product_warehouse_repository;
        $this->stock_repository = $stock_repository;

        $this->product_repository = $product_repository;
        $this->stockCommonService = $stockCommonService;

        $this->stock_repo = $stock_repo;
        $this->warehouse_repo = $warehouse_repo;
    }

    public function allProductQuantity($request)
    {

        $product = $this->product_repository->get($request->product);

        $product->load([
            'availableWarehouses',
        ]);

        $product_warehouses_id = $product->availableWarehouses->pluck('id');

        list($product_warehouses, $pagination) = $this->product_warehouse_repository->paginate(15, [
            'product_id' => $request->product
        ], [], 'warehouse_id', 'asc', null, $product_warehouses_id);


        $product_warehouses->load([
            'warehouse.currentLanguage'
        ]);


        $product_warehouses = ProductWarehouseResource::collection($product_warehouses);

        return ApiResponse::format(200, $product_warehouses, null, $pagination);
    }


    public function availableProductQuantity($request)
    {
        $product = $this->product_repository->get($request->product);

        $product->load([
            'availableWarehouses.currentLanguage',
            'variations.availableWarehouses.currentLanguage'
        ]);

        $product = ProductStockResource::make($product);

        return ApiResponse::format(200, $product);
    }

    public function productStockLogs($request)
    {
        list($stock, $pagination) = parent::paginate($this->stock_repo, null, false, [
            'product_id' => $request->product
        ]);

        $stock->load([
            'fromWarehouse.currentLanguage',
            'toWarehouse.currentLanguage',
            'company.language'
        ]);


        $stock = StockResource::collection($stock);

        return ApiResponse::format(200, $stock, null, $pagination);
    }

    public function addQuantity($request)
    {
        $to_warehouse_product = [];

        // Get all products list that require to increase in ($to_warehouse)
        $product_qty = $this->product_warehouse_repository->getBulk([
            $request->product_id
        ], $request->to_warehouse)->first();


        // calculate new quantity and old quantity
        list($new_quantity) = $this->stockCommonService->newQuantity($product_qty, $request->qty);

        $to_warehouse_product [] = $this->stockCommonService
            ->prepareProductsQuantity($request->product_id, $new_quantity, $request->to_warehouse, $request->available);

        $stock_data = $this->stockCommonService->prepareStockData(
            $request->product_id,
            $request->qty,
            $request->to_warehouse,
            null,
            null,
            $this->add_type
        );

        $stock_data['company_id'] = $request->company_id;

        $target_warehouse = $this->warehouse_repo->get($request->to_warehouse);

        // detach the Product from the warehouse
        // attach the $to_warehouse_product from the warehouse
        $this->warehouse_repo->detachProductWarehouse($target_warehouse, [
            $request->product_id
        ]);

        $this->stock_repo->create($stock_data);

        $this->warehouse_repo->attachProductWarehouse($target_warehouse, $to_warehouse_product);

        $this->ProductNotifications($request->product_id, $request->to_warehouse);

        return ApiResponse::format(200, null, 'Add Stock Done Successfully');
    }

    public function moveQuantity($request)
    {
        $to_warehouse_product = [];
        $from_warehouse_product = [];

        $product_qty = $this->product_warehouse_repository->getBulk([
            $request->product_id
        ], $request->to_warehouse)->first();

        $product_qty_from = $this->product_warehouse_repository->getBulk([
            $request->product_id
        ], $request->from_warehouse)->first();

        // Moving to Warehouse
        if (isset($product_qty_from)) {
            if ($product_qty_from->projected_quantity < $request->qty) {
                StockHelper::NoAvailableQuantity();
            } else {
                // moving Process
                list($new_quantity_from) = $this->stockCommonService
                    ->newQuantity($product_qty_from, $request->qty, 'MOVED');

                $from_warehouse_product [] = $this->stockCommonService
                    ->prepareProductsQuantity($request->product_id, $new_quantity_from, $request->from_warehouse, $request->available);

                list($new_quantity_to) = $this->stockCommonService
                    ->newQuantity($product_qty, $request->qty);

                $to_warehouse_product [] = $this->stockCommonService
                    ->prepareProductsQuantity($request->product_id, $new_quantity_to, $request->to_warehouse, $request->available);

                $stock_data = $this->stockCommonService->prepareStockData(
                    $request->product_id,
                    $request->qty,
                    $request->to_warehouse,
                    $request->from_warehouse,
                    null,
                    $this->moved_type
                );

                // detach the Product from the warehouse
                // attach the $to_warehouse_product from the warehouse
                $target_warehouse_to = $this->warehouse_repo->get($request->to_warehouse);
                $this->warehouse_repo->detachProductWarehouse($target_warehouse_to, [
                    $request->product_id
                ]);
                $this->warehouse_repo->attachProductWarehouse($target_warehouse_to, $to_warehouse_product);

                // detach the Product from the warehouse
                // attach the $from_warehouse_product from the warehouse
                $target_warehouse_from = $this->warehouse_repo->get($request->from_warehouse);
                $this->warehouse_repo->detachProductWarehouse($target_warehouse_from, [
                    $request->product_id
                ]);
                $this->warehouse_repo->attachProductWarehouse($target_warehouse_from, $from_warehouse_product);

                $this->stock_repo->create($stock_data);

                $this->ProductNotifications($request->product_id, $request->to_warehouse);
            }
        } else { // there's no product warehouse
            StockHelper::NoAvailableQuantity();
        }

        return ApiResponse::format(200, null, 'Moving Stock done Successfully');
    }

    public function productWarehouseQuantity($request)
    {
        $quantity = 0;
        $product_qty = $this->product_warehouse_repository->getBulk([
            $request->product
        ], $request->warehouse_id)->first();

        if ($product_qty) {
            $quantity = $product_qty->projected_quantity;
        }
        return ApiResponse::format(200, [
            'quantity' => $quantity
        ]);
    }

    public function sellWithAvailability($request)
    {
        $this->product_warehouse_repository->setBulk($request->products, $request->warehouse_id, $request->available);
        return ApiResponse::format(201, [], 'Added successfully');
    }

    public function ProductNotifications($product_id, $to_warehouse){
        $notifications = collect([]);

        $notifications = StockHelper::addProductNotification($notifications, [$product_id], $this->product_repository);

        \Session::put('product_notifications', $notifications);
        \Session::put('to_warehouse', $to_warehouse);

        ProductSubscriptionNotifications::dispatchAfterResponse();
    }

}
