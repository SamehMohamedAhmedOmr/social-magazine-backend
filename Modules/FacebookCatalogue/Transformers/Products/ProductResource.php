<?php

namespace Modules\FacebookCatalogue\Transformers\CMS\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Facades\ProductHelper;

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
        $category_string = $this->mainCategory->currentLanguage->name;
        $category = $this->mainCategory;

        while ($category->parent_id !== null) {
            $category_now = $category->parent;
            $category_string = $category_now->currentLanguage->name .' > '.$category_string;
            $category = $category->parent;
        }

        return [
            'id' => $this->id ,
            'title' => $this->name ?? $this->currentLanguage->name,
            'description' => $this->description ?? $this->currentLanguage->description,
            'condition' => isset($this->condition) ? $this->condition : 'new',
            'brand' => $this->brand->currentLanguage->name,
            'category' => $category_string,
            'google_product_category' => isset($this->google_product_category) ? $this->google_product_category : null,
            'price' => (float)$price,
            'link' => $this->dynamic_url,
            'image_link' => url('/'), // TODO: Firebase
            'availability' => $stock > 0 ? 'in stock' : 'out of stock',
            'inventory' => (integer)$stock,
            'shipping_weight' =>$this->when(isset($this->weight), (string) ($this->unitOfMeasure
                ? $this->weight. " ". $this->unitOfMeasure->currentLanguage->name : null)) ,
        ];
    }
}
