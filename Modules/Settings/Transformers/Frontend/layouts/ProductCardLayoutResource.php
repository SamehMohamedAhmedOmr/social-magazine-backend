<?php

namespace Modules\Settings\Transformers\Frontend\layouts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ProductCardLayoutResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $show_price = $this->pagesLayout->where('key','show_price')->first();
        $show_add_to_cart = $this->pagesLayout->where('key','show_add_to_cart')->first();
        $show_favourite = $this->pagesLayout->where('key','show_favourite')->first();

        return [
            'show_price' => ($show_price->value) ? true : false,
            'show_add_to_cart' => ($show_add_to_cart->value) ? true : false,
            'show_favourite' => ($show_favourite->value) ? true : false,
        ];
    }
}
