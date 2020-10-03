<?php

namespace Modules\WareHouse\Services\Frontend;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;

class CartCalculationService extends LaravelServiceClass
{
    protected $checkoutOrderService;

    public function __construct(CheckoutOrderService $checkoutOrderService)
    {
        $this->checkoutOrderService = $checkoutOrderService;
    }

    public function cartCalculation($request)
    {
        list($items, $warehouse , $actual_total_price ,
            $shipping_price, $shipping_rule_id,
            $discount, $vat, $final_total_price) = $this->checkoutOrderService->cartItemCalculation($request);

        $points_needed = 0;
        $points_to_price = 0;

        if (isset($request->level_id) || isset($request->points_needed)) {
            $request->merge(['final_total_price' => $final_total_price, 'user_id' => Auth::id()]);
            $data = $this->checkoutOrderService->redeemService->validation($request);
            $points_needed = (integer) $data['points_needed'];
            $points_to_price = (float) $data['points_price'];
        }

        $price_after_loyality = (float) ($final_total_price - $points_to_price);

        return ApiResponse::format(200, [
            'total_price' => $actual_total_price,
            'discount' => $discount,
            'shipping_price' => $shipping_price,
            'shipping_role' => 'Shipping '.$shipping_price.' LE',
            'vat' => $vat,
            'final_total_price' => $final_total_price,
            'points_needed' => $points_needed,
            'loyality_discount' => $points_to_price,
            'price_after_loyality' => $price_after_loyality,
        ]);
    }
}
