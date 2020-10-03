<?php

namespace Modules\Reports\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CmsDashboardResource extends Resource
{
    private $orders;
    private $client_counts;
    private $carts;
    public function __construct($orders, $carts, $client_counts)
    {
        parent::__construct($orders);

        $this->orders = $orders;
        $this->carts = $carts;
        $this->client_counts = $client_counts;
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
            'orders' =>  $this->orders,
            'carts' => $this->carts,
            'new_customers_last_month' => number_format($this->client_counts)
        ];
    }
}
