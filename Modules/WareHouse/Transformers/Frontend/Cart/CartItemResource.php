<?php

namespace Modules\WareHouse\Transformers\Frontend\Cart;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\CMS\Brand\BrandResource;
use Modules\Catalogue\Transformers\Frontend\CategoryResource;
use Modules\WareHouse\Facades\CartHelper;

class CartItemResource extends Resource
{
    protected $topping;
    protected $cart_item_id;
    protected $requested_quantity;
    protected $has_toppings;
    protected $stock_quantity;
    protected $price;

    public function __construct(
        $resource,
        $topping,
        $cart_item_id,
        $requested_quantity,
        $has_toppings,
        $stock_quantity,
        $price
    )
    {
        parent::__construct($resource);

        $this->topping = $topping;
        $this->cart_item_id = $cart_item_id;
        $this->requested_quantity = $requested_quantity;
        $this->has_toppings = $has_toppings;
        $this->stock_quantity = $stock_quantity;
        $this->price = $price;
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function prepare()
    {
        list($name, $slug, $description) = CartHelper::prepareLanguages($this->languages);
        // $images = CartHelper::prepareImages($this->images);

        $favorite = (count($this->favorites)) ? true : false;

        return [
            'cart_item_id' => $this->cart_item_id,
            'id' => $this->id,

            'quantity' => $this->requested_quantity,
            'has_toppings' => $this->has_toppings,

            'is_active' => $this->is_active,

            'name' => $name,
            'description' => $description,
            'slug' => $slug,

            "weight" => $this->weight,
            "sku" => $this->sku,
            'price' => $this->price,

            //  'images' => $images,
            'favourite' => $favorite,

            'stock_qty' => $this->stock_quantity,

            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'category' => CategoryResource::make($this->whenLoaded('mainCategory')),

            'toppings' => ToppingResource::collection($this->topping),
       ];
    }
}
