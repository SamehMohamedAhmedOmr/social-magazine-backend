<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Loyality\Transformers\Common\PointsLogResource;
use Modules\Users\Transformers\AddressResource;
use Modules\Users\Transformers\UserResource;

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

        $final_price = $this->calculateFinalTotalPrice();

        return [
            'order_id' => $this->id,
            'date' => $this->delivery_date,
            'user_id' => ($this->user) ? $this->user->id : '',
            'status' => $this->status ? $this->status->currentLanguage->name : '',
            'address_id' => $this->address_id,

            'payment_method' => $payment_method,

            'payment_method_id' => $this->payment_method_id,

            'time_section_id' => $this->time_section_id,

            'shipping_rate' => round($this->shipping_price, 2),
            'total_price' => round($this->total_price, 2),
            'discount' => round($this->discount, 2),
            'vat' => round($this->vat, 2),

            'final_total_price' => round($final_price, 2),

            'loyality_discount' => round($this->loyality_discount, 2),

            'price_after_loyality' => round($final_price - $this->loyality_discount, 2),

            'cart' => OrderItemResource::collection($this->whenLoaded('orderItems')),

            'address' => AddressResource::make($this->whenLoaded('address')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'shipment_id' => $this->whenLoaded('shipment', function (){
                return $this->shipment ? $this->shipment->id : null;
            }),
            'loyality_log' => PointsLogResource::make($this->whenLoaded('loyality')),
        ];
    }

    private function calculateFinalTotalPrice()
    {
        return ($this->total_price + $this->shipping_price + $this->vat) - $this->discount;
    }
}
