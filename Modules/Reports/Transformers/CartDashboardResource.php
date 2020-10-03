<?php

namespace Modules\Reports\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CartDashboardResource extends Resource
{
    private $pending_cart_item_count;
    private $pending_carts_count;
    public function __construct($pending_cart_item_count, $pending_carts_count)
    {
        parent::__construct($pending_cart_item_count);
        $this->pending_cart_item_count = $pending_cart_item_count;
        $this->pending_carts_count = $pending_carts_count;
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
            'pending_cart_item_count' => $this->pending_cart_item_count,
            'pending_carts_count' => $this->pending_carts_count
        ];
    }
}
