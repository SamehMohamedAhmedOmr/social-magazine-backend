<?php

namespace Modules\WareHouse\Services\Frontend;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FrontendUtilities\Repositories\PromocodesUsageRepository;
use Modules\FrontendUtilities\Services\Frontend\PromocodeService;
use Modules\Loyality\Services\Common\RedeemService;
use Modules\Settings\Repositories\PaymentMethodRepository;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\Users\Repositories\AddressRepository;
use Modules\WareHouse\Repositories\CartItemRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Services\Common\OrderManageService;
use Modules\WareHouse\Services\Frontend\Payments\WeAccept;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;
use Modules\WareHouse\Facades\CheckoutErrorsHelper;
use Auth;
use Throwable;

class CheckoutOrderService extends LaravelServiceClass
{
    protected $order_repo;
    protected $address_repo;
    protected $cartService;
    protected $promocode_service;
    protected $taxesList_repo;
    protected $cart_item_repo;
    protected $orderManageService;
    protected $payment_repo;
    protected $we_accept;
    protected $districtRepository;
    protected $promocodesUsageRepository;
    public $redeemService;
    protected $orderStatusRepository;

    public function __construct(
        OrderRepository $order_repo,
        AddressRepository $address_repo,
        DistrictRepository $districtRepository,
        TaxesListRepository $taxesList_repo,
        PromocodeService $promocode_service,
        CartItemRepository $cart_item_repo,
        OrderManageService $orderManageService,
        CartService $cartService,
        PaymentMethodRepository $payment_repo,
        PromocodesUsageRepository $promocodesUsageRepository,
        WeAccept $we_accept,
        RedeemService $redeemService,
        OrderStatusRepository $orderStatusRepository
    )
    {
        $this->order_repo = $order_repo;
        $this->cartService = $cartService;
        $this->address_repo = $address_repo;
        $this->promocode_service = $promocode_service;
        $this->taxesList_repo = $taxesList_repo;
        $this->cart_item_repo = $cart_item_repo;
        $this->payment_repo = $payment_repo;
        $this->orderManageService = $orderManageService;
        $this->districtRepository = $districtRepository;

        $this->redeemService = $redeemService;
        $this->we_accept = $we_accept;
        $this->promocodesUsageRepository = $promocodesUsageRepository;
        $this->orderStatusRepository = $orderStatusRepository;
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

            list(
                $items, $warehouse, $actual_total_price,
                $shipping_price, $shipping_rule_id,
                $discount, $vat, $total_price, $promocode_id
                ) = $this->cartItemCalculation($request);

            $this->validateCartItem($items);
            $points_to_price = 0;

            if (isset($request->level_id) || isset($request->points_needed)) {
                $request->merge(['final_total_price' => $total_price, 'user_id' => Auth::id()]);
                $redeem = $this->redeemService->validation($request);
                $points_to_price = (float)$redeem['points_price'];
            }

            $payment = $this->payment_repo->get($request->payment_method);
            $order_status = $this->orderStatusRepository->get('PENDING', [], 'key', []);
            $payment_order_id = null;
            $payment_data = [];

            if ($payment->key === 'WE_ACCEPT') {
                $order_status = $this->orderStatusRepository->get('PENDING_CREDIT', [], 'key', []);
                $payment_data = $this->we_accept->create($total_price);
                $payment_order_id = $payment_data['order_id'];
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
                $order_status->id,
                $payment_order_id,
                $points_to_price
            );

            $order = $this->order_repo->create($order_object);

            if (isset($request->level_id) || isset($request->points_needed)) {
                $this->redeemService->redeem((object)[
                    'points_needed' => $redeem['points_needed'],
                    'user_id' => Auth::id(),
                    'order_id' => $order->id
                ]);
            }


            // create Items and Items toppings
            $cart_items_id = $this->orderManageService->createItems($order->id, $items);

            // update warehouse quantity
            $this->orderManageService->updateWarehouseQuantities($items, $warehouse);

            if ($request->promocode && isset($promocode_id)) {
                $promocode_usage_object = $this->orderManageService->preparePromocodeUsage(
                    $order->id,
                    $promocode_id,
                    Auth::id(),
                    $discount
                );

                $this->promocodesUsageRepository->create($promocode_usage_object);
            }

            // empty cart
            $this->cart_item_repo->deleteBulk($cart_items_id);

            if ($payment->key === 'WE_ACCEPT') {
                return ApiResponse::format(
                    201,
                    ['link' => $payment_data['frame_url']],
                    'Order Created Successfully'
                );
            }

            $order->load(
                'paymentMethod',
                'address',
                'user',
                'orderItems.product.currentLanguage',
                'orderItems.toppings.currentLanguage',
                'loyality',
                'status.currentLanguage'
            );

            $order_response = OrderResource::make($order);

            return ApiResponse::format(201, $order_response, 'Order Successfully');
        });
    }

    ##### Validate Order
    public function cartItemCalculation($request)
    {
        $discount = 0;
        $vat = 0;

        if ($request->address_id) {
            $address = $this->address_repo->get($request->address_id, ['user_id' => \Auth::id()]);
            $district_id = $address->district_id;
        } else {
            $district_id = UtilitiesHelper::getDistrictId();
        }

        list($items, $cart_items, $warehouses) = $this->cartService->getCart($district_id, 1);

        if (!count($items)) {
            CheckoutErrorsHelper::noCartItem();
        }

        if (!$warehouses) {
            CheckoutErrorsHelper::itemOutOfStock();
        }

        $warehouse = $warehouses[0];
        // will throw error if there's no price
        list($actual_total_price, $shipping_price,
            $shipping_rule_id) = $this->cartService->calculateCart($cart_items, $warehouse->district_id);

        $total_price = $actual_total_price;

        $district = $this->getDistrict($warehouse->district_id);

        $country_id = ($district) ? $district->country_id : $warehouse->country_id;

        $taxes = $this->taxesList_repo->get($country_id, [
            'key' => 'VAT'
        ], 'country_id', [
            'language', 'taxType', 'amountType'
        ]);

        $tax_type = ($taxes) ? $taxes->taxType->key : null;
        $tax_amount_type = ($taxes) ? $taxes->amountType->key : null;


        if ($tax_type == 'ON_NET_TOTAL' && isset($taxes)) {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        $promocode_id = null;
        // promocode
        if ($request->promocode) {
            $promocode_response = $this->promocode_service->validate($request->promocode, $total_price);

            list($total_price, $discount) = $this->orderManageService->calculatePromocode($total_price, $promocode_response);

            if ($tax_type == 'ON_TOTAL_AFTER_DISCOUNT' && isset($taxes)) {
                // call taxes calculated
                list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
            }

            if (!$promocode_response['has_free_shipping']) {
                $total_price += $shipping_price;
            } else {
                $shipping_price = 0;
            }

            $promocode_id = $promocode_response['promocode_id'];
        }

        if ($tax_type == 'ON_TOTAL_AFTER_SHIPPING' && isset($taxes)) {
            list($total_price, $vat) = $this->orderManageService->calculateTaxes($total_price, $tax_amount_type, $taxes->price);
        }

        return [
            $items,
            $warehouse,
            $actual_total_price,
            $shipping_price,
            $shipping_rule_id,
            $discount,
            $vat,
            $total_price,
            $promocode_id
        ];
    }

    private function getDistrict($district_id)
    {
        return $this->districtRepository->getDistrict($district_id);
    }

    ###### Validate Cart
    public function validateCartItem($items)
    {
        $items_id = collect([]);
        $toppings_id = collect([]);

        foreach ($items as $item) {
            if (!$item['is_active']) {
                CheckoutErrorsHelper::notActiveItem();
            }
            if ($item['stock_quantity'] < $item['quantity']) {
                CheckoutErrorsHelper::itemOutOfStock();
            }

            $items_id->push($item['product_id']);

            $toppings = collect($item['toppings']);

            foreach ($toppings as $topping) {
                $toppings_id->push($topping['topping_id']);

                if (!$topping['is_active']) {
                    CheckoutErrorsHelper::notActiveItemTopping();
                }
                if ($topping['stock_quantity'] < $item['quantity']) {
                    CheckoutErrorsHelper::itemToppingOutOfStock();
                }
            }
        }

        $check_items_duplicate = $items_id->duplicates();
        $check_toppings_duplicate = $toppings_id->duplicates();

        if (count($check_items_duplicate)) {
            CheckoutErrorsHelper::itemsDuplicated();
        }

        if (count($check_toppings_duplicate)) {
            CheckoutErrorsHelper::toppingDuplicate();
        }
    }
}
