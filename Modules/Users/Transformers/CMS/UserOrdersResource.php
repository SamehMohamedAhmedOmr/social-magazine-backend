<?php

namespace Modules\Users\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class UserOrdersResource extends Resource
{
    private $orders_count;
    private $orders_total_price;

    public function __construct(
        $orders_count,
        $orders_total_price
    )
    {
        parent::__construct($orders_count);

        $this->orders_count = $orders_count;
        $this->orders_total_price = $orders_total_price;

    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'orders_count' => $this->orders_count,
            'orders_total_price' =>  $this->preparePrice($this->orders_total_price),
        ];
    }

    public function preparePrice($price)
    {
        $check = $this->is_decimal($price);
        if ($check) {
            return round($price, 2);
        }
        return $price;
    }

    public function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }
}
