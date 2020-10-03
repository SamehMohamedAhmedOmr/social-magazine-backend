<?php

namespace Modules\WareHouse\Services\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\PurchaseInvoicesRepository;
use Modules\WareHouse\Repositories\PurchaseOrderProductRepository;
use Modules\WareHouse\Repositories\PurchaseReceiptRepository;
use Modules\WareHouse\Transformers\PurchaseInvoiceResource;

class PurchaseInvoicesService extends LaravelServiceClass
{
    private $purchase_invoice_repo;
    private $purchase_receipt_repo;
    private $purchase_order_product_repo;
    private $purchase_order_service;

    private $added_status = 0;
    private $submitted_status = 1;

    public function __construct(
        PurchaseInvoicesRepository $purchase_invoice_repo,
        PurchaseReceiptRepository $purchase_receipt_repo,
        PurchaseOrderProductRepository $purchase_order_product_repo,
        PurchaseOrderService $purchase_order_service
    )
    {
        $this->purchase_invoice_repo = $purchase_invoice_repo;

        $this->purchase_receipt_repo = $purchase_receipt_repo;
        $this->purchase_order_product_repo = $purchase_order_product_repo;
        $this->purchase_order_service = $purchase_order_service;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($purchase_invoices, $pagination) = parent::paginate($this->purchase_invoice_repo, null, false);
        } else {
            $purchase_invoices = $this->purchase_invoice_repo->all([], $this->purchase_invoice_repo->relationShips());
            $pagination = null;
        }

        $purchase_invoices = PurchaseInvoiceResource::collection($purchase_invoices);
        return ApiResponse::format(200, $purchase_invoices, [], $pagination);
    }

    public function store()
    {
        // store purchase_invoice main data
        $purchase_invoice_data = request()->all();

        list($total_price) = $this->calculateTotalPrice(request('purchase_receipt_id'));
        $purchase_invoice_data['total_price'] = $total_price;

        $purchase_invoice = $this->purchase_invoice_repo->create($purchase_invoice_data);

        $purchase_invoice = PurchaseInvoiceResource::make($purchase_invoice);
        return ApiResponse::format(200, $purchase_invoice, 'purchase invoice Added Successfully');
    }

    public function show($id)
    {
        $purchase_invoice = $this->purchase_invoice_repo->get($id, [], 'id', $this->purchase_invoice_repo->relationShips());
        $purchase_invoice = PurchaseInvoiceResource::make($purchase_invoice);
        return ApiResponse::format(200, $purchase_invoice);
    }

    /**
     * update Purchase Invoice
     *
     * @param $id
     * @return JsonResponse
     */
    public function update($id)
    {
        if (request()->has('purchase_receipt_id')) {
            // update purchase_invoice main data
            list($total_price) = $this->calculateTotalPrice(request('purchase_receipt_id'));
            $purchase_invoice_data['total_price'] = $total_price;
        }

        $purchase_invoice_data['status'] = request('status');

        $purchase_invoice = $this->purchase_invoice_repo->update($id, $purchase_invoice_data, [], 'id', $this->purchase_invoice_repo->relationShips());

        $purchase_invoice = PurchaseInvoiceResource::make($purchase_invoice);
        return ApiResponse::format(200, $purchase_invoice, 'purchase invoice updated Successfully');
    }

    /**
     * delete Purchase Invoice
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $purchase_invoice = $this->purchase_invoice_repo->delete($id);
        return ApiResponse::format(200, $purchase_invoice, 'purchase_invoice Deleted Successfully');
    }


    public function calculateTotalPrice($purchase_receipt_id)
    {
        $purchase_receipt = $this->purchase_receipt_repo->get($purchase_receipt_id, [], 'id', $this->purchase_receipt_repo->relationShips());

        $purchase_order = $purchase_receipt->purchaseOrder;

        // Get product price and Quantity to calculate
        $purchase_receipt_products = $purchase_receipt->products;

        $product_ids = $purchase_receipt_products->pluck('id');
        $products_pivot = $purchase_receipt_products->pluck('pivot');

        $purchase_order_products = $this->purchase_order_product_repo->getBulk($product_ids, $purchase_order->id);

        $products = $products_pivot->map(function ($item) use ($purchase_order_products,$purchase_receipt_products) {
            $purchase_order_product = $purchase_order_products->where('product_id', $item->product_id)->first();
            return [
                'price' => $purchase_order_product->price,
                'quantity' => $item->accepted_quantity,
            ];
        })->toArray();

        return $this->purchase_order_service->calculateTotalPrice(
            $products,
            $purchase_order->discount_type,
            $purchase_order->discount,
            $purchase_order->tax,
            $purchase_order->shippingRule
        );
    }
}
