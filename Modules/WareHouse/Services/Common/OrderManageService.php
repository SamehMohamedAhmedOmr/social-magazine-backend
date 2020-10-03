<?php

namespace Modules\WareHouse\Services\Common;

use Carbon\Carbon;
use Auth;
use Modules\WareHouse\Repositories\OrderItemRepository;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;
use Modules\WareHouse\Transformers\Frontend\Checkout\PrepareOrderItemCreationResource;
use Modules\WareHouse\Transformers\Frontend\Checkout\PrepareOrderCreationResource;
use UtilitiesHelper;

class OrderManageService
{
    private $order_item_repo;
    private $product_warehouse_repository;

    public function __construct(
        OrderItemRepository $order_item_repo,
        ProductWarehouseRepository $product_warehouse_repository
    )
    {
        $this->order_item_repo = $order_item_repo;
        $this->product_warehouse_repository  = $product_warehouse_repository;
    }


    public function prepareOrderObject(
        $request,
        $warehouse,
        $total_price,
        $shipping_price,
        $shipping_rule_id,
        $discount,
        $vat,
        $order_status = 1,
        $payment_order_id = null,
        $points_to_price = 0
    )
    {
        $delivery_date = ($request->delivery_date) ? $request->delivery_date : Carbon::now()->toDateString();

        list($device_id, $device_os, $app_version) = UtilitiesHelper::prepareDeviceHeader();

        $warehouse_id = $warehouse->id;

        return PrepareOrderCreationResource::prepare([
            'delivery_date' => $delivery_date,

            'shipping_price' => $shipping_price,

            'total_price' => $total_price,

            'discount' => $discount,

            'vat' => $vat,

            'device_id' => $device_id,
            'device_os' => $device_os,
            'app_version' => $app_version,

            'user_id' => Auth::id(),
            'payment_method_id' => $request->payment_method,
            'address_id' => $request->address_id,
            'time_section_id' => $request->time_section,

            'shipping_rule_id' => $shipping_rule_id,
            'warehouse_id' => $warehouse_id,

            'status' => $order_status ?? 1,
            'payment_order_id' => $payment_order_id,
            'loyality_discount' => $points_to_price,
        ]);
    }

    public function calculatePromocode($total_price, $promocode_response)
    {
        if ($promocode_response['discount_type'] == 'percentage') {
            $discount = $total_price * ($promocode_response['discount_rate']/100);
            $total_price = $total_price - $discount;
        } else { // Fixed
            $discount = $promocode_response['discount_rate'];
            $total_price = $total_price - $promocode_response['discount_rate'];
        }
        return [$total_price, $discount];
    }

    public function calculateTaxes($total_price, $tax_amount_type, $rate)
    {
        if ($tax_amount_type == 'PERCENTAGE') {
            $vat = $total_price * ($rate/100);
            $total_price = $total_price + $vat;
        } else { // Fixed
            $vat = $rate;
            $total_price = $total_price + $vat;
        }
        return [$total_price, $vat];
    }

    public function createItems($order_id, $items)
    {
        $cart_items = [];
        foreach ($items as $item) {
            if (isset($item['cart_item_id'])) {
                $cart_items [] = $item['cart_item_id'];
            }

            $item_object = $this->prepareOrderItemObject($order_id, $item);

            $new_item = $this->order_item_repo->create($item_object);

            $toppings = $this->prepareOrderItemToppingObject($new_item->id, $item['toppings'], $item['quantity']);

            $new_item->toppings()->attach($toppings);
        }
        return $cart_items;
    }

    private function prepareOrderItemObject($order_id, $item)
    {
        return PrepareOrderItemCreationResource::prepare([
            'order_id' => $order_id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'has_toppings' => $item['has_toppings'],
            'buying_price' => $item['buying_price'],
        ]);
    }

    public function preparePromocodeUsage($order_id, $promocode_id, $user_id, $discount)
    {
        return [
            'order_id' => $order_id,
            'promocode_id' => $promocode_id,
            'user_id' => $user_id,
            'discount' => $discount,
        ];
    }

    private function prepareOrderItemToppingObject($order_item_id, $toppings, $quantity)
    {
        $target_topping = [];

        $toppings = collect($toppings);
        foreach ($toppings as $key => $topping) {
            $target_topping[$key] = array(
                'order_item_id' => $order_item_id,
                'price' => $topping['price'],
                'buying_price' => $topping['buying_price'],
                'quantity' => $quantity,
                'topping_id' => $topping['topping_id'],
            );
        }

        return $target_topping;
    }

    public function updateWarehouseQuantities($items, $warehouse)
    {
        $warehouse->load('products');
        $products_id = [];
        $product_warehouse = [];

        foreach ($items as $item) {
            $item_warehouse = $this->product_warehouse_repository->getProduct($warehouse, $item['product_id']);

            if ($item_warehouse->is_sell_with_availability && $item_warehouse->pivot->available) continue;

            if ($item_warehouse) {
                $products_id [] = $item_warehouse->id;
                $new_quantity = $item_warehouse->pivot->projected_quantity - $item['quantity'];

                $product_warehouse[] = array(
                    'product_id' => $item_warehouse->id,
                    'projected_quantity' => $new_quantity,
                );

                $toppings = collect($item['toppings']);

                foreach ($toppings as $topping) {
                    $product_topping = $this->product_warehouse_repository->getProduct($warehouse, $topping['topping_id']);
                    $products_id [] = $product_topping->id;
                    $topping_new_quantity = $product_topping->pivot->projected_quantity - $item['quantity'];

                    $product_warehouse[] = array(
                        'product_id' => $product_topping->id,
                        'projected_quantity' => $topping_new_quantity,
                    );
                }
            }
        }

        $this->product_warehouse_repository->updateQuantity($warehouse, $products_id, $product_warehouse);
    }
}
