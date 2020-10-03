<?php

namespace Modules\WareHouse\Transformers\CMS\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Loyality\Transformers\Common\PointsLogResource;
use Modules\Settings\Transformers\PaymentMethodResource;
use Modules\Users\Transformers\AddressResource;
use Modules\Users\Transformers\UserResource;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderItemResource;

class OrderResource extends Resource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $payment_method = null;
        if ($this->relationLoaded('paymentMethod')){
            if ($this->paymentMethod){
                $currentLanguage = $this->paymentMethod->currentLanguage;
                $payment_method = ($currentLanguage) ? $currentLanguage->name : null;
            }
        }

        return [
            'order_id' => $this->id,
            'date' => $this->delivery_date,
            'user_id' => ($this->user) ? $this->user->id : '',
            'status' => OrderStatusResource::make($this->whenLoaded('status')),
            'address_id' => $this->address_id,

            'payment_method_id' => $this->payment_method_id,

            'payment_method' => $payment_method,

            'time_section_id' => $this->time_section_id,

            'shipping_rate' => round($this->shipping_price, 2),
            'total_price' => round($this->total_price, 2),
            'discount' => round($this->discount, 2),
            'vat' => round($this->vat, 2),

            'final_total_price' => round($this->calculateFinalTotalPrice(), 2),

            'cart' => OrderItemResource::collection($this->whenLoaded('orderItems')),

            'address' => AddressResource::make($this->whenLoaded('address')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'shipment_id' => $this->whenLoaded('shipment', function (){
                return $this->shipment ? $this->shipment->id : null;
            }),

            'created_at' => Carbon::make($this->created_at)->toDateString(),

            'loyality_log' => PointsLogResource::make($this->whenLoaded('loyality')),

        ];
    }

    private function calculateFinalTotalPrice()
    {
        return ($this->total_price + $this->shipping_price + $this->vat) - $this->discount;
    }
}
