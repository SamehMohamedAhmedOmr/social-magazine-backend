<?php

namespace Modules\WareHouse\Services\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Services\CMS\EmailService;
use Modules\Settings\Repositories\ShippingRuleRepository;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\WareHouse\Repositories\PurchaseOrderRepository;
use Modules\WareHouse\Transformers\PurchaseOrderResource;

class PurchaseOrderService extends LaravelServiceClass
{
    private $purchase_order_repo;
    private $taxes_list_repo;
    private $shipping_rule_repo;
    private $email_service;

    public function __construct(
        PurchaseOrderRepository $purchase_order_repo,
        TaxesListRepository $taxes_list_repo,
        ShippingRuleRepository $shipping_rule_repo,
        EmailService $email_service
    )
    {
        $this->purchase_order_repo = $purchase_order_repo;
        $this->taxes_list_repo = $taxes_list_repo;
        $this->shipping_rule_repo = $shipping_rule_repo;

        $this->email_service = $email_service;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($purchase_orders, $pagination) = parent::paginate($this->purchase_order_repo, null);
        } else {
            $purchase_orders = $this->purchase_order_repo->all([], $this->purchase_order_repo->relationShips());
            $pagination = null;
        }

        $purchase_orders = PurchaseOrderResource::collection($purchase_orders);
        return ApiResponse::format(200, $purchase_orders, [], $pagination);
    }

    public function store()
    {
        list($tax, $shipping_rule) =$this->getTaxesAndShippingRule(request('tax_id'), request('shipping_rule_id'));
        // store warehouse main data
        list($total_price) = $this->calculateTotalPrice(
            request('products'),
            request('discount_type'),
            request('discount'),
            $tax,
            $shipping_rule
        );

        $purchase_order_data = array_merge(request()->all(), ['total_price' => $total_price]);
        $purchase_order = $this->purchase_order_repo->create($purchase_order_data, $this->purchase_order_repo->relationShips());

        $this->purchase_order_repo->attachProduct($purchase_order, request('products'));

        $purchase_order = PurchaseOrderResource::make($purchase_order);
        return ApiResponse::format(200, $purchase_order, 'Purchase Order Added Successfully');
    }

    public function show($id)
    {
        $purchase_order = $this->purchase_order_repo->get($id, [], 'id', $this->purchase_order_repo->relationShips());
        $purchase_order = PurchaseOrderResource::make($purchase_order);
        return ApiResponse::format(200, $purchase_order);
    }

    /**
     * update Purchase order
     *
     * @param $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $purchase_order = $this->purchase_order_repo->get($id);

        if (request()->has('products')) {
            $tax_id = request()->has('tax_id') ? request('tax_id') : $purchase_order->tax_id;
            $shipping_rule_id = request()->has('shipping_rule_id') ? request('shipping_rule_id') : $purchase_order->shipping_rule_id;

            list($tax, $shipping_rule) =$this->getTaxesAndShippingRule($tax_id, $shipping_rule_id);

            if (request()->has('discount_type') && request()->has('discount')) {
                list($total_price) = $this->calculateTotalPrice(
                    request('products'),
                    request('discount_type'),
                    request('discount'),
                    $tax,
                    $shipping_rule
                );
            } else {
                list($total_price) = $this->calculateTotalPrice(
                    request('products'),
                    $purchase_order->discount_type,
                    $purchase_order->discount,
                    $tax,
                    $shipping_rule
                );
            }

            $purchase_order_data = array_merge(request()->all(), ['total_price' => $total_price]);

            $purchase_order = $this->purchase_order_repo->update($id, $purchase_order_data, [], 'id', $this->purchase_order_repo->relationShips());

            // update purchase order repo
            $this->purchase_order_repo->detachProduct($purchase_order);
            $this->purchase_order_repo->attachProduct($purchase_order, request('products'));
        } else {
            // store warehouse main data
            $purchase_order = $this->purchase_order_repo->update($id, request()->all(), [], 'id', $this->purchase_order_repo->relationShips());
        }

        $purchase_order = PurchaseOrderResource::make($purchase_order);
        return ApiResponse::format(200, $purchase_order, 'Purchase Order updated Successfully');
    }

    /**
     * delete Purchase order
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $purchase_order = $this->purchase_order_repo->delete($id);
        return ApiResponse::format(200, $purchase_order, 'Purchase Order Deleted Successfully');
    }

    public function getTaxesAndShippingRule($tax_id, $shipping_rule_id)
    {
        $tax = (isset($tax_id)) ? $this->taxes_list_repo->get($tax_id) : null;
        $shipping_rule = (isset($shipping_rule_id)) ? $this->shipping_rule_repo->get($shipping_rule_id) : null;

        return [$tax,$shipping_rule];
    }

    public function calculateTotalPrice($products, $discount_type, $discount, $tax = null, $shipping = null)
    {
        $total_price = 0;
        $discount_price_rate = 0;
        $shipping_price = 0;
        $tax_price = 0;

        foreach ($products as $product) {
            $product_total_price = $product['quantity'] * $product['price'];
            $total_price += $product_total_price;
        }
        $total_price_before_extra_fees = $total_price;

        if ($discount_type) { // percentage = 1
            $discount_price = ($discount / 100) * $total_price;
            $total_price = $total_price - $discount_price;
            $discount_price_rate = $discount_price;
        } else { // fixed = 0
            $total_price = $total_price - $discount;
            $discount_price_rate = $discount;
        }

        if (isset($shipping)) {
            $shipping_price = $shipping->price;
            $total_price += $shipping_price;
        }

        if (isset($tax)) {
            if ($tax->tax_amount_type_id == 1) { // Fixed = 1
                $tax_price = $tax->price;
                $total_price += $tax_price;
            } else { // percentage = 2
                $addition_taxes = ($tax->price / 100) * $total_price;
                $total_price += $addition_taxes;
                $tax_price = $addition_taxes;
            }
        }

        $total_price = floor($total_price * 100) / 100;

        return [$total_price, $total_price_before_extra_fees, $discount_price_rate, $shipping_price, $tax_price];
    }

    public function sendEmail($id)
    {
        $purchase_order_name = '['.config("app.name").'] Purchase Order PO-'.$id;

        $pdf = $this->generatePurchaseOrderPDF($id, 1, true);
        $this->email_service->email(
            request('email'),
            'Warehouse',
            'Emails.Purchase-order',
            $purchase_order_name,
            [
                'recipient' => 'Customer',
                'title' => $purchase_order_name
            ],
            $pdf,
            $purchase_order_name
        );

        Storage::delete('pdf/'.$purchase_order_name.'.pdf');

        return ApiResponse::format(200, [], 'Email send Successfully');
    }

    public function getWithPDF($id)
    {
        $with_price = request('with_price', 0);
        $pdf = $this->generatePurchaseOrderPDF($id, $with_price);
        $data = chunk_split(base64_encode(($pdf)));
        return ApiResponse::format(200, ['pdf' => $data], 'PDF Generated');
    }

    private function generatePurchaseOrderPDF($id, $with_price, $to_email = false)
    {
        $purchase_order_name = '['.config("app.name").'] Purchase Order PO-'.$id;

        $purchase_order = $this->purchase_order_repo->get($id, [], 'id', $this->purchase_order_repo->relationShips(1));

        // Get product price and Quantity to calculate
        $products = $purchase_order->products->pluck('pivot');
        $products = $products->map(function ($item) {
            return [
                'price' => $item->price,
                'quantity' => $item->quantity
            ];
        })->toArray();

        list($total_price, $total_price_before_extra_fees,
            $discount_price_rate, $shipping_price, $tax_price) = $this->calculateTotalPrice(
                $products,
                $purchase_order->discount_type,
                $purchase_order->discount,
                $purchase_order->tax,
                $purchase_order->shippingRule
            );

        return generatePDF('warehouse::pdf.purchaseOrder', [
            'purchase_order' => $purchase_order,
            'price' => $with_price,
            'total_price' => $total_price,
            'total_price_before_extra_fees' => $total_price_before_extra_fees,
            'discount_price_rate' => $discount_price_rate,
            'shipping_price' => $shipping_price,
            'tax_price' => $tax_price,
            'lang' => 1
        ], $purchase_order_name, $to_email);
    }
}
