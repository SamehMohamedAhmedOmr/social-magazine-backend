<?php

namespace Modules\Catalogue\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Facades\ProductHelper;
use \Modules\Catalogue\Transformers\Frontend\Facades\VariantByProductResource;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class ProductResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */

    public function toArray($request)
    {
        $price = ProductHelper::price($this);
        $stock = ProductHelper::stockQuantity($this);
        $favorite = ProductHelper::favorite($this);

        return [
            'id' => $this->id ,
            'name' => isset($this->name) ? $this->name : $this->currentLanguage->name,
            'slug' => isset($this->slug) ? $this->slug : $this->currentLanguage->slug,
            'description' => isset($this->description) ? $this->description :  $this->currentLanguage->description,
            'sku' => (string) $this->sku,
            'price' => $price,
            'weight' => (float) $this->weight,
            'has_toppings' => $this->topping_menu_id ? true : false,
            'is_favourite' => $this->when($favorite !== null, $favorite),
            'is_sell_with_availability' => (boolean)$this->is_sell_with_availability,
            'stock_qty' => $stock,
            'unit_of_measure' => $this->unitOfMeasure ? $this->unitOfMeasure->currentLanguage->name : null,
            'variants' => $this->parent_id == null ? VariantByProductResource::toArray($this->variantValues) : [],
            'main_category' => CategoryResource::make($this->whenLoaded('mainCategory')),
            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'parent_id' => isset($this->parent_id) != null ? $this->parent_id : null ,
            'parent_slug' => isset($this->parent_id) != null ? $this->variantTo->currentLanguage->slug :  null,
            'topping_menu' => ToppingMenuResource::make($this->whenLoaded('toppingMenu')),
            'variations_products' => ProductResource::collection($this->whenLoaded('variations')),
            'images' => GalleryResource::collection($this->whenLoaded('images')),
        ];
    }
}
