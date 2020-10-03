<?php

namespace Modules\WareHouse\Services\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;
use Modules\WareHouse\Repositories\PurchaseOrderProductRepository;
use Modules\WareHouse\Repositories\PurchaseReceiptProductRepository;
use Modules\WareHouse\Repositories\PurchaseReceiptRepository;
use Modules\WareHouse\Repositories\StockRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Transformers\PrepareProductQuantityResource;
use Modules\WareHouse\Transformers\PreparePurchaseReceiptProductResource;
use Modules\WareHouse\Transformers\PrepareStockResource;
use Modules\WareHouse\Transformers\PurchaseReceiptResource;

class PurchaseReceiptService extends LaravelServiceClass
{
    private $purchase_receipt_repo;
    private $purchase_order_product_repo;
    private $purchase_receipt_product_repo;
    private $purchase_order_service;
    private $product_warehouse_repo;
    private $warehouse_repo;
    private $stock_repo;

    private $added_status = 0;
    private $submitted_status = 1;
    private $cancelled_status = 2;


    public function __construct(
        PurchaseReceiptRepository $purchase_receipt_repo,
        PurchaseOrderProductRepository $purchase_order_product_repo,
        PurchaseReceiptProductRepository $purchase_receipt_product_repo,
        ProductWarehouseRepository $product_warehouse_repo,
        WarehouseRepository $warehouse_repo,
        StockRepository $stock_repo,
        PurchaseOrderService $purchase_order_service
    )
    {
        $this->purchase_receipt_repo = $purchase_receipt_repo;
        $this->purchase_order_product_repo = $purchase_order_product_repo;
        $this->purchase_receipt_product_repo = $purchase_receipt_product_repo;
        $this->purchase_order_service = $purchase_order_service;

        $this->product_warehouse_repo = $product_warehouse_repo;
        $this->warehouse_repo = $warehouse_repo;
        $this->stock_repo = $stock_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($purchase_receipts, $pagination) = parent::paginate($this->purchase_receipt_repo, null, false);
        } else {
            $purchase_receipts = $this->purchase_receipt_repo->all([], $this->purchase_receipt_repo->relationShips());
            $pagination = null;
        }

        $purchase_receipts = PurchaseReceiptResource::collection($purchase_receipts);
        return ApiResponse::format(200, $purchase_receipts, [], $pagination);
    }

    /**
     * store Purchase Receipt
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store()
    {
        list($products, $quantities) = $this->extractProductQuantity();

        list($validate, $validation_msg, $purchase_receipt_products) = $this->validateQuantityAmount($products, $quantities);

        if ($validate == false) {
            throw ValidationException::withMessages($validation_msg);
        }

        // store Purchase Receipt main data
        $purchase_receipt = $this->purchase_receipt_repo->create(request()->all(), $this->purchase_receipt_repo->relationShips());

        $this->purchase_receipt_repo->attachProduct($purchase_receipt, $purchase_receipt_products);

        $purchase_receipt = PurchaseReceiptResource::make($purchase_receipt);
        return ApiResponse::format(200, $purchase_receipt, 'Purchase Receipt Added Successfully');
    }

    public function show($id)
    {
        $purchase_receipt = $this->purchase_receipt_repo->get($id, [], 'id', $this->purchase_receipt_repo->relationShips());
        $purchase_receipt = PurchaseReceiptResource::make($purchase_receipt);
        return ApiResponse::format(200, $purchase_receipt);
    }

    /**
     * update Purchase Receipt
     *
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id)
    {
        DB::beginTransaction();

        // update purchase receipt main data
        $purchase_receipt = $this->purchase_receipt_repo->update($id, request()->all(), [], 'id', $this->purchase_receipt_repo->relationShips());

        // store products
        if (request()->has('products')) {
            $purchase_receipt->products()->detach();

            list($products, $quantities) = $this->extractProductQuantity();
            list($validate, $validation_msg, $purchase_receipt_products) = $this->validateQuantityAmount($products, $quantities);

            if ($validate == false) {
                DB::rollBack();
                throw ValidationException::withMessages($validation_msg);
            }

            $purchase_receipt->products()->attach($purchase_receipt_products);
        }

        DB::commit();
        $purchase_receipt = PurchaseReceiptResource::make($purchase_receipt);
        return ApiResponse::format(200, $purchase_receipt, 'Purchase Receipt updated Successfully');
    }

    /**
     * change Purchase Receipt status
     *
     * @param $id
     * @return JsonResponse
     */
    public function changeStatus($id)
    {
        // store purchase receipt main data
        $purchase_receipt = $this->purchase_receipt_repo->update($id, ['status' => request('status')], [], 'id', $this->purchase_receipt_repo->relationShips());

        if (request('status') == $this->submitted_status) {
            $this->insertIntoStock($purchase_receipt);
        }

        $purchase_receipt = PurchaseReceiptResource::make($purchase_receipt);
        return ApiResponse::format(200, $purchase_receipt, 'Purchase Receipt updated Successfully');
    }

    public function insertIntoStock($purchase_receipt)
    {
        $product_warehouse = [];
        $stock = [];

        $purchase_receipt_products = $purchase_receipt->products;

        $products_id = $purchase_receipt_products->pluck('id');

        $warehouse = $purchase_receipt->purchaseOrder->warehouse;

        $to_warehouse = $warehouse->id;

        $products_quantity = $this->product_warehouse_repo->getBulk($products_id, $to_warehouse);

        foreach ($purchase_receipt_products as $product) {
            $accepted_quantity = $product->pivot->accepted_quantity;

            $stock [] = $this->prepareStockData(
                $product->id,
                $accepted_quantity,
                $to_warehouse,
                null,
                $purchase_receipt->purchase_order_id,
                $purchase_receipt->company_id
            );

            $product_qty = $products_quantity->where('product_id', $product->id)->first();
            $newQuantity = ($product_qty) ?  $product_qty->projected_quantity + $accepted_quantity : $accepted_quantity;

            $product_warehouse [] = $this->prepareProductsQuantity($product->id, $newQuantity, $to_warehouse);
        }

        if (isset($products_id)) {
            $this->warehouse_repo->detachProductWarehouse($warehouse, $products_id->toArray());
        }
        $this->warehouse_repo->attachProductWarehouse($warehouse, $product_warehouse);

        $this->stock_repo->createMany($stock);
    }

    public function prepareStockData($product_id, $quantity, $to_warehouse, $from_warehouse, $purchase_order_id, $company_id)
    {
        return PrepareStockResource::prepare([
            'product_id' => $product_id,
            'stock_quantity' => $quantity,
            'from_warehouse' => $from_warehouse,
            'to_warehouse' => $to_warehouse,
            'type' => $this->added_status,
            'is_active' => 1,
            'purchase_order_id' => $purchase_order_id,
            'company_id' => $company_id,
        ]);
    }

    public function prepareProductsQuantity($product, $quantity, $warehouse)
    {
        return PrepareProductQuantityResource::prepare([
            'product_id' => $product,
            'projected_quantity' => $quantity,
            'warehouse_id' => $warehouse
        ]);
    }

    /**
     * Delete Purchase Receipt
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $purchase_receipt = $this->purchase_receipt_repo->delete($id);
        return ApiResponse::format(200, $purchase_receipt, 'Purchase Receipt Deleted Successfully');
    }

    private function extractProductQuantity()
    {
        $products = collect(request('products'));

        $products_id = $products->pluck('product_id');

        $quantities = $products->pluck('accepted_quantity');

        return [$products_id, $quantities];
    }

    private function validateQuantityAmount($products, $quantities)
    {
        $validate = true;
        $validation_msg = [];
        $purchase_receipt_products_object = [];

        $purchase_order_products = $this->purchase_order_product_repo->getBulk($products, request('purchase_order_id'));

        if (count($purchase_order_products) > 0) {
            $purchase_receipts = $this->purchase_receipt_repo->getPurchaseReceiptIds(request('purchase_order_id'))->toArray();
            $purchase_receipt_products = $this->purchase_receipt_product_repo->getBulk($purchase_receipts);

            foreach ($products as $key => $product) {

                // validate purchase order product quantities
                list($purchase_order_product, $validate,
                    $error_msg, $error_key, $continue) = $this->validatePOProductQuantity(
                        $purchase_order_products,
                        $product,
                        $quantities,
                        $key,
                        $validate
                    );

                if ($error_key && $error_msg) {
                    $validation_msg [$error_key] = $error_msg;
                }
                if ($continue) {
                    continue;
                }


                // validate purchase order amount in all receipt belongs to same product and same purchase order
                $no_receipt = true;
                list($pr_product, $error_msg, $error_key, $validate, $no_receipt) = $this->validatePRProductQuantity(
                    $purchase_receipt_products,
                    $product,
                    $quantities,
                    $key,
                    $purchase_order_product,
                    $no_receipt,
                    $validate
                );

                if ($error_key && $error_msg) {
                    $validation_msg [$error_key] = $error_msg;
                }

                if (isset($pr_product)) {
                    $purchase_receipt_products_object [] = $pr_product;
                }

                if ($no_receipt && $validate) {
                    $purchase_receipt_products_object [] = $this->prepareProductQuantity(
                        $product,
                        $quantities[$key],
                        $purchase_order_product->quantity
                    );
                }
            }
        } else {
            $validate = false;
            $validation_msg ['purchase_order'] = ['No purchase order for this product'];
        }

        return [$validate, $validation_msg, $purchase_receipt_products_object];
    }

    private function validatePOProductQuantity($purchase_order_products, $product, $quantities, $key, $validate)
    {
        $validation_msg = null;
        $validation_key = null;
        $continue = false;

        $purchase_order_product = $purchase_order_products->where('product_id', $product)->first();
        if (isset($purchase_order_product)) {
            if ($purchase_order_product->quantity < $quantities[$key]) {
                $validate = false;
                $validation_msg  = ['accepted quantity is greater than purchase order quantity'];
                $validation_key  =  'products.'.$key.'.accepted_quantity';
            }
        } else {
            $validate = false;
            $validation_msg  = ['No purchase order for this product'];
            $validation_key  = 'products.'.$key.'.product_id';

            $continue = true;
        }

        return [$purchase_order_product,$validate,$validation_msg,$validation_key,$continue];
    }

    private function validatePRProductQuantity($purchase_receipt_products, $product, $quantities, $key, $purchase_order_product, $no_receipt, $validate)
    {
        $validation_msg = null;
        $validation_key = null;
        $purchase_receipt_products_object = null;
        $total_quantity = 0;

        if (count($purchase_receipt_products)) {
            $purchase_receipt_product = $purchase_receipt_products->where('product_id', $product);
            if (count($purchase_receipt_product) > 0) {
                $no_receipt = false;
                foreach ($purchase_receipt_product as $item) {
                    $total_quantity += $item->accepted_quantity;
                }
                $purposed_quantity = $total_quantity + $quantities[$key];
                if ($purchase_order_product->quantity < $purposed_quantity) {
                    $validate = false;
                    $validation_msg = ['accepted quantity is greater than purchase order quantity'];
                    $validation_key =  'products.'.$key.'.accepted_quantity';
                } else {
                    $purchase_receipt_products_object = $this->prepareProductQuantity(
                        $product,
                        $quantities[$key],
                        $purchase_order_product->quantity,
                        $total_quantity
                    );
                }
            }
        }

        return [$purchase_receipt_products_object, $validation_msg, $validation_key, $validate,$no_receipt];
    }

    private function prepareProductQuantity($product, $accepted_quantity, $requested_quantity, $previous_total = 0)
    {
        $remaining_quantity = $requested_quantity - ($accepted_quantity + $previous_total);

        return PreparePurchaseReceiptProductResource::prepare([
            'product_id' => $product,
            'accepted_quantity' => $accepted_quantity,
            'requested_quantity' => $requested_quantity,
            'remaining_quantity' => $remaining_quantity,
        ]);
    }

    public function getWithPDF($id)
    {
        $purchase_receipt = $this->purchase_receipt_repo->get($id, [], 'id', $this->purchase_receipt_repo->relationShips());

        $purchase_receipt = $purchase_receipt->load('purchaseOrder');

        $purchase_order = $purchase_receipt->purchaseOrder;

        // Get product price and Quantity to calculate
        $purchase_receipt_products = $purchase_receipt->products;

        $product_ids = $purchase_receipt_products->pluck('id');
        $products_pivot = $purchase_receipt_products->pluck('pivot');

        $purchase_order_products = $this->purchase_order_product_repo->getBulk($product_ids, $purchase_order->id);

        $products = $products_pivot->map(function ($item) use ($purchase_order_products,$purchase_receipt_products) {
            $purchase_order_product = $purchase_order_products->where('product_id', $item->product_id)->first();
            $purchase_receipt_product = $purchase_receipt_products->where('id', $item->product_id)->first();
            $purchase_receipt_product_lang = $purchase_receipt_product->languages->where('language_id', 1)->first();
            $name = isset($purchase_receipt_product_lang) ? $purchase_receipt_product_lang->name : null;
            return [
                'product_id' => $item->product_id,
                'sku' => $purchase_receipt_product->sku,
                'name' => $name,
                'accepted_quantity' => $item->accepted_quantity,
                'price' => $purchase_order_product->price,
                'quantity' => $item->accepted_quantity,
            ];
        })->toArray();


        list($total_price, $total_price_before_extra_fees,
            $discount_price_rate, $shipping_price, $tax_price) = $this->purchase_order_service->calculateTotalPrice(
                $products,
                $purchase_order->discount_type,
                $purchase_order->discount,
                $purchase_receipt->tax,
                $purchase_receipt->shippingRule
            );


        $purchase_receipt_name = '['.config("app.name").'] Purchase Receipt PO-'.$id;


        $pdf = generatePDF('warehouse::pdf.purchaseReceipt', [
            'purchase_receipt' => $purchase_receipt,
            'products' => $products,
            'total_price' => $total_price,
            'shipping_price' => $shipping_price,
            'tax_price' => $tax_price,
            'purchase_order' => $purchase_order
        ], $purchase_receipt_name);

        $data = chunk_split(base64_encode(($pdf)));
        return ApiResponse::format(200, ['pdf' => $data], 'PDF Generated');
    }
}
