<?php

namespace Modules\Loyality\Services\Common;

use Illuminate\Support\Facades\Auth;

class RedeemCalculationService
{
    public function index($request)
    {
        $total_price = 0;
        $userCart = Auth::user()->cart->load('cartItems.product');
        

    }
}
