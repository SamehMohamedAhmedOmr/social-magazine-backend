<?php

namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\WareHouse\Facades\CheckoutErrorsHelper;
use Modules\WareHouse\Repositories\OrderItemRepository;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Repositories\ProductWarehouseRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Services\Common\OrderManageService;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;

class OrderItemCMSService extends LaravelServiceClass
{
    private $order_repo;
    private $orderItemRepository;
    private $warehouseRepository;
    private $orderManageService;
    private $taxesList_repo;
    private $product_warehouse_repository;
    private $prepareEditOrderCMSService;


    public function __construct(
        OrderRepository $order_repo,
        WarehouseRepository $warehouseRepository,
        OrderManageService $orderManageService,
        TaxesListRepository $taxesList_repo,
        ProductWarehouseRepository $product_warehouse_repository,
        PrepareEditOrderCMSService $prepareEditOrderCMSService,
        OrderItemRepository $orderItemRepository
    )
    {
        $this->order_repo = $order_repo;
        $this->orderItemRepository = $orderItemRepository;
        $this->warehouseRepository = $warehouseRepository;

        $this->orderManageService = $orderManageService;
        $this->taxesList_repo = $taxesList_repo;
        $this->product_warehouse_repository = $product_warehouse_repository;

        $this->prepareEditOrderCMSService = $prepareEditOrderCMSService;
    }

    public function edit($request)
    {
        return \DB::transaction(function () use ($request) {
            $order = $this->order_repo->get($request->order_id, [], 'id', [
                'orderItems.toppings',
                'promoCodeUsage.promocode'
            ]);

            $order_item = $order->orderItems->where('product_id', $request->product_id)->first();


            if (isset($order_item)) {
                $new_quantity = $this->getNewQuantity($request->quantity, $order_item);
                if ($new_quantity != 0) {
                    $warehouse = $this->validateWarehouseQuantity($request, $order, $new_quantity);
                } else {
                    $warehouse = $this->warehouseRepository->get($order->warehouse_id);
                }

                $order = $this->editOrderItem($request, $order, $warehouse, $order_item);
            } else {
                $new_quantity = $this->getNewQuantity($request->quantity);
                $warehouse = $this->validateWarehouseQuantity($request, $order, $new_quantity);
                $order = $this->addOrderItem($request, $order, $warehouse);
            }

            $order->load('paymentMethod', 'address', 'user', 'orderItems.product.languages', 'status.languages');

            $order_response = OrderResource::make($order);

            return ApiResponse::format(200, $order_response, 'Update Order item Successfully');
        });
    }

    private function addOrderItem($request, $order, $warehouse)
    {
        $new_product = $this->prepareEditOrderCMSService->prepareSingleProduct($request);

        $item_price = $this->prepareEditOrderCMSService->calculateSingleItemPrice($new_product);

        $actual_total_price = $order->total_price + $item_price;

        list($discount, $vat, $shipping_price) = $this->calculateAccessories($order, $actual_total_price, $warehouse->country_id);

        $order = $this->order_repo->update($order->id, [
            'discount' => $discount,
            'vat' => $vat,
            'shipping_price' => $shipping_price,
            'total_price' => $actual_total_price
        ]);

        $new_product['order_id'] = $order->id;
        $order_item = $this->orderItemRepository->create($new_product);
        if (count($new_product['toppings'])) {
            $this->orderItemRepository->attachTopping($order_item, $new_product['toppings']);
        }

        $this->prepareEditOrderCMSService->restoreSingleProductQuantity($new_product, $warehouse, 0, 1, 1); // type 0 => minus new quantity


        $order->load('orderItems.toppings');
        return $order;
    }


    private function editOrderItem($request, $order, $warehouse, $order_item)
    {
        $new_product = $this->prepareEditOrderCMSService->prepareSingleProduct($request);

        $item_price = $this->prepareEditOrderCMSService->calculateSingleItemPrice($new_product);

        $previous_price = $this->calculatePreviousPrice($order_item);

        $order_price = $order->total_price - $previous_price;

        $actual_total_price = $order_price + $item_price;

        list($discount, $vat, $shipping_price) = $this->calculateAccessories($order, $actual_total_price, $warehouse->country_id);

        $order = $this->order_repo->update($order->id, [
            'discount' => $discount,
            'vat' => $vat,
            'shipping_price' => $shipping_price,
            'total_price' => $actual_total_price
        ]);

        $new_product['order_id'] = $order->id;

        $this->prepareEditOrderCMSService->restoreSingleProductQuantity($order_item, $warehouse, 1, 1, 0); // minus
        $previous_toppings = $this->preparePreviousTopping($order_item->toppings);

        $order_item = $this->orderItemRepository->update($order_item->id, $new_product);

        if (count($new_product['toppings'])) {
            $this->orderItemRepository->detachTopping($order_item, $previous_toppings);
            $this->orderItemRepository->attachTopping($order_item, $new_product['toppings']);
        }

        $this->prepareEditOrderCMSService->restoreSingleProductQuantity($new_product, $warehouse, 0, 1, 1); // add

        $order->load('orderItems.toppings');
        return $order;
    }


    private function validateWarehouseQuantity($request, $order, $new_quantity)
    {
        $warehouse = $this->warehouseRepository->get($order->warehouse_id);

        $warehouse_product = $warehouse->products->where('id', $request->product_id)->first();

        if (isset($warehouse_product)) {
            $projected_quantity = $warehouse_product->pivot->projected_quantity;

            if (!$warehouse_product->is_active) {
                CheckoutErrorsHelper::notActiveItem();
            }

            if ($projected_quantity < $new_quantity) {
                CheckoutErrorsHelper::itemOutOfStock();
            } else { // check topping available
                if (isset($request->toppings)) {
                    foreach ($request->toppings as $topping) {
                        $warehouse_topping = $warehouse->products->where('id', $topping)->first();
                        if (isset($warehouse_topping)) {
                            if (!$warehouse_topping->is_active) {
                                CheckoutErrorsHelper::notActiveItemTopping();
                            }

                            $projected_topping_quantity = $warehouse_topping->pivot->projected_quantity;
                            if ($projected_topping_quantity < $new_quantity) {
                                CheckoutErrorsHelper::itemToppingOutOfStock();
                            }
                        }
                    }
                }
            }
        } else {
            CheckoutErrorsHelper::itemOutOfStock();
        }
        return $warehouse;
    }

    private function getNewQuantity($quantity, $order_item = null)
    {
        $new_quantity = 0;
        if (!$order_item) {
            $new_quantity = $quantity;
        } else {
            if ($order_item->quantity < $quantity) {
                $new_quantity = $quantity - $order_item->quantity;
            }
        }
        return $new_quantity;
    }


    private function calculatePreviousPrice($order_item)
    {
        $product_price = $order_item->price * $order_item->quantity;

        $topping_price = 0;

        foreach ($order_item->toppings as $topping) {
            $product_topping_price = $topping->pivot->price * $topping->pivot->quantity;

            $topping_price += $product_topping_price;
        }

        return $product_price + $topping_price;
    }

    private function calculateAccessories($order, $actual_total_price, $country_id)
    {
        $vat = 0;
        $discount = 0;

        $total_price = $actual_total_price;

        $taxes = $this->taxesList_repo->get($country_id, [
            'key' => 'VAT'
        ], 'country_id', [
            'language', 'taxType', 'amountType'
        ]);

        $tax_type = ($taxes) ? $taxes->taxType->key : null;
        $tax_amount_type = ($taxes) ? $taxes->amountType->key : null;


        if ($tax_type == 'ON_NET_TOTAL') {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        $promocode_usage = $order->promoCodeUsage;

        $shipping_price = $order->shipping_price;

        // promocode
        if ($promocode_usage) {
            $promocode = $promocode_usage->promocode;

            $promocode_response = $this->preparePromocodeResponse($promocode);

            list($total_price, $discount) = $this->orderManageService->calculatePromocode($total_price, $promocode_response);

            if ($tax_type == 'ON_TOTAL_AFTER_DISCOUNT') {
                // call taxes calculated
                list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
            }

            if (!$promocode_response['has_free_shipping']) {
                $total_price += $shipping_price;
            } else {
                $shipping_price = 0;
            }
        }

        if ($tax_type == 'ON_TOTAL_AFTER_SHIPPING') {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        return [
            $discount,
            $vat,
            $shipping_price,
        ];
    }

    private function preparePromocodeResponse($promocode)
    {
        return [
            'discount_type' => $promocode->discount_type ? 'percentage' : 'fixed',
            'discount_rate' => $promocode->reward,
            'has_free_shipping' => $promocode->is_free_shipping,
        ];
    }


    private function preparePreviousTopping($toppings)
    {
        $previous_topping = [];
        foreach ($toppings as $topping) {
            $previous_topping [] = $topping->pivot->topping_id;
        }
        return $previous_topping;
    }


    public function delete($order_item_id)
    {
        return \DB::transaction(function () use ($order_item_id) {
            $order_item = $this->orderItemRepository->get($order_item_id, [], 'id', [
                'order.warehouse'
            ]);

            $order = $order_item->order;
            $warehouse = $order->warehouse;

            $order = $this->deleteOrderItem($order, $warehouse, $order_item);

            $order_response = OrderResource::make($order);

            return ApiResponse::format(200, $order_response, 'Update Order item Successfully');
        });
    }

    private function deleteOrderItem($order, $warehouse, $order_item)
    {
        $previous_price = $this->calculatePreviousPrice($order_item);

        $actual_total_price = $order->total_price - $previous_price;

        list($discount, $vat, $shipping_price) = $this->calculateAccessories($order, $actual_total_price, $warehouse->country_id);

        $order = $this->order_repo->update($order->id, [
            'discount' => $discount,
            'vat' => $vat,
            'shipping_price' => $shipping_price,
            'total_price' => $actual_total_price
        ]);


        $this->prepareEditOrderCMSService->restoreSingleProductQuantity($order_item, $warehouse, 1, 1, 0); // type = 1 Add old quantity

        $this->orderItemRepository->delete($order_item->id);

        $order->load('orderItems.toppings');

        return $order;
    }
}
