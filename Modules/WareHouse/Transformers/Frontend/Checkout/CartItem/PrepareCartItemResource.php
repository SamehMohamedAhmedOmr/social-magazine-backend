<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout\CartItem;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\CMS\Brand\BrandResource;
use Modules\Catalogue\Transformers\Frontend\CategoryResource;

class PrepareCartItemResource extends Resource
{
    protected $topping;
    protected $cart_item_id;
    protected $requested_quantity;
    protected $has_toppings;
    protected $stock_quantity;
    protected $target_warehouse;
    protected $price;
    protected $buyingPrice;

    public function __construct(
        $resource,
        $topping,
        $cart_item_id,
        $requested_quantity,
        $has_toppings,
        $stock_quantity,
        $target_warehouse,
        $price,
        $buyingPrice
    )
    {
        parent::__construct($resource);

        $this->topping = $topping;
        $this->cart_item_id = $cart_item_id;
        $this->requested_quantity = $requested_quantity;
        $this->has_toppings = $has_toppings;
        $this->stock_quantity = $stock_quantity;
        $this->target_warehouse = $target_warehouse;
        $this->price = $price;
        $this->buyingPrice = $buyingPrice;
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function prepare()
    {

        $favorite = (count($this->favorites)) ? true : false;

        return [
            'cart_item_id' => $this->cart_item_id,
            'product_id' => $this->id,
            'id' => $this->id,

            'quantity' => $this->requested_quantity,
            'has_toppings' => $this->has_toppings,

            'is_active' => $this->is_active,

            'name' => $this->currentLanguage->name ?? '',
            'description' => $this->currentLanguage->description ?? '',
            'slug' => $this->currentLanguage->slug ?? '',

            "weight" => $this->weight,
            "sku" => $this->sku,
            'price' => $this->price,
            'buying_price' => $this->buyingPrice,

            //  'images' => $images,
            'favourite' => $favorite,

            'stock_quantity' => $this->stock_quantity,
            'warehouse' => CartItemWarehouseResource::make($this->target_warehouse),

            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'category' => CategoryResource::make($this->whenLoaded('mainCategory')),

            'toppings' => PrepareToppingResource::collection($this->topping),
       ];
    }
}
