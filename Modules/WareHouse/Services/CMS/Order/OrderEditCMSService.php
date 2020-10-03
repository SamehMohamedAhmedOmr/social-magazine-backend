<?php

namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\Users\Repositories\AddressRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\OrderRepository;

use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Services\Common\OrderManageService;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;
use CheckoutErrorsHelper;

class OrderEditCMSService extends LaravelServiceClass
{
    private $order_repo;
    private $address_repo;

    protected $taxesList_repo;
    protected $orderManageService;
    protected $district_repository;
    protected $warehouse_repository;
    protected $prepareEditOrderCMSService;
    protected $orderStatusRepository;


    public function __construct(
        OrderRepository $order_repo,
        AddressRepository $address_repo,
        TaxesListRepository $taxesList_repo,
        OrderManageService $orderManageService,
        DistrictRepository $district_repository,
        WarehouseRepository $warehouse_repository,
        PrepareEditOrderCMSService $prepareEditOrderCMSService,
        OrderStatusRepository $orderStatusRepository
    )
    {
        $this->order_repo = $order_repo;
        $this->address_repo = $address_repo;
        $this->taxesList_repo = $taxesList_repo;


        $this->orderManageService = $orderManageService;

        $this->district_repository = $district_repository;
        $this->warehouse_repository = $warehouse_repository;

        $this->prepareEditOrderCMSService = $prepareEditOrderCMSService;
        $this->orderStatusRepository = $orderStatusRepository;
    }


    public function editOrder($request)
    {
        return \DB::transaction(function () use ($request) {
            $order = $this->order_repo->get($request->order, [], 'id', [
                'orderItems.toppings'
            ]);

            // process after change address
            if ($request->address_id != $order->address_id) {
                $order = $this->editOrderAddress($request, $order);
            } else { // normal edit
                $order = $this->normalEdit($request, $order);
            }

            $order->load([
                'address',
                'paymentMethod',
                'timeSection',
                'user',
                'orderItems.product.languages',
                'status.languages'
            ]);

            $order_response = OrderResource::make($order);

            return ApiResponse::format(200, $order_response, 'Update Order Successfully');
        });
    }

    private function editOrderAddress($request, $order)
    {
        $discount = $order->discount;
        $vat = $order->vat;

        $address = $this->address_repo->get($request->address_id);

        $address->load('district.shippingRule');

        $district_id = $address->district_id;

        $district = $address->district;

        if (!(isset($district) && isset($district->shippingRule))) {
            CheckoutErrorsHelper::noDistrict();
        }

        $warehouse = $this->getTargetWarehouse($order->orderItems, $district_id);


        $shipping_rule_id = $district->shippingRule->id;
        $shipping_price = $district->shippingRule->price;
        // Check Product available


        $taxes = $this->taxesList_repo->get($district->country_id, [
            'key' => 'VAT'
        ], 'country_id', [
            'language', 'taxType', 'amountType'
        ]);

        $tax_type = $taxes->taxType->key;
        $tax_amount_type = $taxes->amountType->key;

        $order->load('orderItems.toppings');

        $items = $this->prepareEditOrderCMSService->prepareProducts($order->orderItems);
        // will throw error if there's no price
        $actual_total_price = $this->prepareEditOrderCMSService->calculateItemsPrice($items);


        $total_price = $actual_total_price;

        if ($tax_type == 'ON_NET_TOTAL') {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        $total_price = $total_price + $discount;


        if ($tax_type == 'ON_TOTAL_AFTER_DISCOUNT') {
            // call taxes calculated
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }


        if ($tax_type == 'ON_TOTAL_AFTER_SHIPPING') {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        $old_warehouse_id = $order->warehouse_id;

        // prepare order object
        $order_object = $this->orderManageService->prepareOrderObject(
            $request,
            $warehouse,
            $actual_total_price,
            $shipping_price,
            $shipping_rule_id,
            $discount,
            $vat
        );

        $order = $this->order_repo->update($order->id, $order_object);


        if ($warehouse->id != $old_warehouse_id) {
            // update warehouse quantity
            $old_warehouse = $this->warehouse_repository->get($old_warehouse_id);

            $this->prepareEditOrderCMSService->restoreWarehouseQuantities($items, $warehouse, 0); // type = 0 minus new quantity

            $this->prepareEditOrderCMSService->restoreWarehouseQuantities($items, $old_warehouse, 1); // type = 1 Add old quantity
        }

        return $order;
    }

    private function normalEdit($request, $order)
    {
        return $this->order_repo->update($order->id, [
            'payment_method_id' => $request->payment_method,
            'time_section_id' => $request->time_section,
            'delivery_date' => $request->delivery_date,
        ]);
    }


    /**
     * get Target Warehouse
     *
     * @param $products
     * @param $district_id
     * @return mixed|null
     */
    private function getTargetWarehouse($products, $district_id)
    {
        $warehouses = $this->detectWarehouse($district_id);
        $target_warehouse = null;
        foreach ($products as $product) {

            $item_exist = true;
            $topping_exist = true;

            foreach ($warehouses as $warehouse) {
                // product is eager loading in detectWarehouse method
                $warehouse_product = $warehouse->products->where('id', $product['product_id'])->first();
                if (isset($warehouse_product)) {
                    $projected_quantity = $warehouse_product->pivot->projected_quantity;

                    if (!$warehouse_product->is_active) {
                        CheckoutErrorsHelper::notActiveItem();
                    }

                    if ($projected_quantity < $product['quantity']) {
                        $item_exist = false;
                    } else { // check topping available
                        if (isset($product['toppings'])) {
                            foreach ($product['toppings'] as $topping) {
                                $warehouse_topping = $warehouse->products->where('id', $topping)->first();
                                if (isset($warehouse_topping)) {
                                    if (!$warehouse_topping->is_active) {
                                        CheckoutErrorsHelper::notActiveItemTopping();
                                    }

                                    $projected_topping_quantity = $warehouse_topping->pivot->projected_quantity;
                                    if ($projected_topping_quantity < $product['quantity']) {
                                        $topping_exist = false;
                                    }
                                }
                            }
                        }
                    }
                    $target_warehouse = $warehouse;
                }

            }

            if (!$item_exist) {
                CheckoutErrorsHelper::itemOutOfStock();
            }

            if (!$topping_exist) {
                CheckoutErrorsHelper::itemToppingOutOfStock();
            }
        }

        if (!$target_warehouse) {
            CheckoutErrorsHelper::itemOutOfStock();
        }

        return $target_warehouse;
    }

    private function detectWarehouse($district_id = null)
    {
        $district_exist = false;
        $warehouses_id = [];

        if ($district_id) {
            $district = $this->district_repository->getDistrict($district_id);
            $warehouses = $district->warehouse;
            if ($warehouses) {
                $district_exist = true;
                $warehouses_id = $warehouses;
            }
        }

        if ($district_exist == false) {
            $warehouse = $this->warehouse_repository->getDefault();
            $warehouses_id [] = $warehouse;
        }
        return $warehouses_id;
    }

    public function editStatus($request)
    {
        $this->order_repo->updateBulk($request->orders, [
            'order_status_id' => $request->status
        ]);

        // TODO return to Stock Quantity


        return ApiResponse::format(200, null, 'Update Order Status Successfully');
    }

}
