<?php

namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\FrontendUtilities\Repositories\PromocodesUsageRepository;
use Modules\FrontendUtilities\Services\Frontend\PromocodeService;
use Modules\Loyality\Services\Common\RedeemService;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\Users\Repositories\AddressRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\WareHouse\Repositories\CartItemRepository;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Services\Common\OrderManageService;
use Modules\WareHouse\Services\Frontend\CartService;
use Modules\WareHouse\Services\Frontend\CheckoutOrderService;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;
use CheckoutErrorsHelper;
use Throwable;

class OrderCMSService extends LaravelServiceClass
{
    protected $order_repo;
    protected $address_repo;
    protected $cartService;
    protected $promocode_service;
    protected $taxesList_repo;
    protected $cart_item_repo;
    protected $checkoutOrderService;
    protected $orderManageService;
    protected $district_repository;
    protected $warehouse_repository;
    protected $productRepository;
    protected $userRepository;
    protected $orderRepository;
    protected $promocodesUsageRepository;
    protected $redeemService;

    public function __construct(
        OrderRepository $order_repo,
        AddressRepository $address_repo,
        TaxesListRepository $taxesList_repo,
        PromocodeService $promocode_service,
        CartItemRepository $cart_item_repo,
        CheckoutOrderService $checkoutOrderService,
        OrderManageService $orderManageService,
        DistrictRepository $district_repository,
        WarehouseRepository $warehouse_repository,
        UserRepository $userRepository,
        PromocodesUsageRepository $promocodesUsageRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        CartService $cartService,
        RedeemService $redeemService
    )
    {
        $this->order_repo = $order_repo;
        $this->cartService = $cartService;
        $this->address_repo = $address_repo;
        $this->promocode_service = $promocode_service;
        $this->taxesList_repo = $taxesList_repo;

        $this->cart_item_repo = $cart_item_repo;

        $this->checkoutOrderService = $checkoutOrderService;
        $this->orderManageService = $orderManageService;

        $this->district_repository = $district_repository;
        $this->warehouse_repository = $warehouse_repository;

        $this->productRepository = $productRepository;

        $this->userRepository = $userRepository;

        $this->orderRepository = $orderRepository;

        $this->promocodesUsageRepository = $promocodesUsageRepository;
        $this->redeemService = $redeemService;
    }


    /**
     * Handles Checkout
     *
     * @param $request
     * @return mixed
     * @throws Throwable
     */
    public function checkout($request)
    {
        return \DB::transaction(function () use ($request) {
            $discount = 0;
            $vat = 0;

            $address = $this->address_repo->get($request->address_id, ['user_id' => $request->user_id]);

            $address->load('district.shippingRule');

            $district = $address->district;
            $district_id = $address->district_id;

            if (!(isset($district) && isset($district->shippingRule))) {
                CheckoutErrorsHelper::noDistrict();
            }

            $shipping_rule_id = $district->shippingRule->id;

            $shipping_price = $district->shippingRule->price;

            // Check Product available
            $warehouse = $this->getTargetWarehouse($request->products, $district_id);


            $taxes = $this->taxesList_repo->get($district->country_id, [
                'key' => 'VAT'
            ], 'country_id', [
                'language', 'taxType', 'amountType'
            ]);

            $tax_type = ($taxes) ? $taxes->taxType->key : null;
            $tax_amount_type = ($taxes) ? $taxes->amountType->key : null;


            $items = $this->prepareProduct($request->products);
            // will throw error if there's no price
            $actual_total_price = $this->calculatePrice($items);


            $total_price = $actual_total_price;

            if ($tax_type == 'ON_NET_TOTAL') {
                list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
            }

            // promocode
            if ($request->promocode) {
                $user = $this->userRepository->get($request->user_id);

                $promocode_response = $this->promocode_service->validate($request->promocode, $total_price, $user);

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

            $points_to_price = 0;

            if (isset($request->level_id) || isset($request->points_needed)) {
                $request->merge(['final_total_price' => $total_price, 'user_id' => $request->user_id]);
                $redeem = $this->redeemService->validation($request);
                $points_to_price = (float)$redeem['points_price'];
            }


            // prepare order object
            $order_object = $this->orderManageService->prepareOrderObject(
                $request,
                $warehouse,
                $actual_total_price,
                $shipping_price,
                $shipping_rule_id,
                $discount,
                $vat,
                0,
                null,
                $points_to_price
            );

            $order = $this->order_repo->create($order_object);

            // create Items and Items toppings
            $this->orderManageService->createItems($order->id, $items);

            // update warehouse quantity
            $this->orderManageService->updateWarehouseQuantities($items, $warehouse);

            if ($request->promocode && isset($promocode_response)) {
                $promocode_usage_object = $this->orderManageService->preparePromocodeUsage(
                    $order->id,
                    $promocode_response['promocode_id'],
                    $request->user_id,
                    $discount
                );

                $this->promocodesUsageRepository->create($promocode_usage_object);
            }


            $order->load([
                'address',
                'paymentMethod',
                'timeSection',
                'user',
                'orderItems.product.languages',
                'loyality',
                'status.languages'
            ]);

            $order_response = OrderResource::make($order);

            return ApiResponse::format(200, $order_response, 'Order Successfully');
        });
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

    private function prepareProduct($products)
    {
        $product_ids = $this->getProductPayloadIds($products);

        $products_model = $this->productRepository->getBulk('id', $product_ids);

        $products_model->load('priceLists');

        $products_object = collect([]);
        foreach ($products as $product) {
            $target_product = $products_model->where('id', $product['product_id'])->first();
            $price = $this->preparePrice($target_product->priceLists);

            $toppings = isset($product['toppings']) ? $product['toppings'] : [];
            $products_object->push([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'has_toppings' => isset($product['toppings']) ? 1 : 0,
                'price' => $price,
                'toppings' => $this->prepareTopping($toppings, $product['quantity'], $products_model)
            ]);
        }

        return $products_object;
    }

    private function prepareTopping($toppings, $quantity, $products_model)
    {
        $toppings_object = collect([]);
        foreach ($toppings as $topping) {
            $target_product = $products_model->where('id', $topping)->first();
            $price = $this->preparePrice($target_product->priceLists);

            $toppings_object->push([
                'topping_id' => $topping,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
        return $toppings_object;
    }

    private function getProductPayloadIds($products)
    {
        $ids = [];
        foreach ($products as $product) {
            $ids [] = $product['product_id'];

            if (isset($product['toppings'])) {
                foreach ($product['toppings'] as $topping) {
                    $ids [] = $topping;
                }
            }
        }
        return $ids;
    }

    private function preparePrice($price_lists)
    {
        $price = 0;
        foreach ($price_lists as $price_list) {
            if ($price_list->key == 'STANDARD_SELLING_PRICE') {
                $price = $price_list->pivot->price;
                break;
            }
        }

        if ($price == 0) {
            CheckoutErrorsHelper::noSellingPriceList();
        }

        return $price;
    }

    public function calculatePrice($products)
    {
        $total_price = 0;

        foreach ($products as $product) {
            $product_price = $this->calculateProductPrice($product);

            $toppings_price = $this->calculateProductToppingPrice($product);

            $item_price = $product_price + $toppings_price;

            $total_price += $item_price;
        }

        return $total_price;
    }

    private function calculateProductPrice($product)
    {
        return $product['price'] * $product['quantity'];
    }

    private function calculateProductToppingPrice($product)
    {
        $topping_price = 0;

        foreach ($product['toppings'] as $topping) {
            $product_topping_price = $topping['price'] * $topping['quantity'];

            $topping_price += $product_topping_price;
        }

        return $topping_price;
    }
}
