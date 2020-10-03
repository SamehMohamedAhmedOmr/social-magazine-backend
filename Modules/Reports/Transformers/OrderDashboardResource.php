<?php

namespace Modules\Reports\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class OrderDashboardResource extends Resource
{
    private $canceled_orders_count;
    private $canceled_orders_total_price;
    private $delivered_orders_count;
    private $delivered_orders_total_price;
    private $pending_orders_count;
    private $pending_orders_total_price;
    private $total_order_counts;
    private $total_order_price;


    public function __construct(
        $canceled_orders_count,
        $canceled_orders_total_price,
        $delivered_orders_count,
        $delivered_orders_total_price,
        $pending_orders_count,
        $pending_orders_total_price,
        $total_order_counts,
        $total_order_price
    )
    {
        parent::__construct($canceled_orders_count);

        $this->canceled_orders_count = $canceled_orders_count;
        $this->canceled_orders_total_price = $canceled_orders_total_price;

        $this->delivered_orders_count = $delivered_orders_count;
        $this->delivered_orders_total_price = $delivered_orders_total_price;

        $this->pending_orders_count = $pending_orders_count;
        $this->pending_orders_total_price = $pending_orders_total_price;

        $this->total_order_counts = $total_order_counts;
        $this->total_order_price = $total_order_price;
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
            'canceled_orders_count' => $this->canceled_orders_count,
            'canceled_orders_total_price' =>  $this->preparePrice($this->canceled_orders_total_price),

            'delivered_orders_count' => $this->delivered_orders_count,
            'delivered_orders_total_price' => $this->preparePrice($this->delivered_orders_total_price),

            'pending_orders_count' => $this->pending_orders_count,
            'pending_orders_total_price' => $this->preparePrice($this->pending_orders_total_price),

            'total_order_counts' => $this->total_order_counts,
            'total_order_price' => $this->preparePrice($this->total_order_price)
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
