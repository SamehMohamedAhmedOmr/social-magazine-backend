<?php

namespace Modules\Catalogue\Transformers\CMS\Product;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\Brand\BrandResource;
use Modules\Catalogue\Transformers\CMS\Category\CategoryResource;
use Modules\Catalogue\Transformers\CMS\ToppingMenu\ToppingMenuResource;
use Modules\Catalogue\Transformers\CMS\UnitOfMeasure\UnitOfMeasureResource;
use Modules\Catalogue\Transformers\CMS\VariantValue\VariantValueResource;
use Modules\Gallery\Transformers\CMS\GalleryResource;
use Modules\WareHouse\Transformers\CMS\Stock\ProductWarehouseResource;

class ProductResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'languages' => ProductNamesResource::collection($this->whenLoaded('languages')),
            'parent_id' => $this->parent_id,
            'is_active' => (boolean)$this->is_active,
            'is_topping' => (boolean)$this->is_topping,
            'is_bundle' => (boolean)$this->is_bundle,
            'is_sell_with_availability' => (boolean)$this->is_sell_with_availability,
            'max_quantity_per_order' => (boolean)$this->is_sell_with_availability ? $this->max_quantity_per_order : 0,
            'weight' => $this->weight,
            'sku' => $this->sku,
            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'main_category' => CategoryResource::make($this->whenLoaded('mainCategory')),
            'unit_of_measure' => UnitOfMeasureResource::make($this->whenLoaded('unitOfMeasure')),
            'toppingMenu' => ToppingMenuResource::make($this->whenLoaded('toppingMenu')),
            'variations' => ProductResource::collection($this->whenLoaded('variations')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'price_lists' => ProductPriceListResource::collection($this->whenLoaded('priceLists')),
            'variant_values' => VariantValueResource::collection($this->whenLoaded('variantValues')),
            'images' => GalleryResource::collection($this->whenLoaded('images')),
            "dynamic_link" => $this->dynamic_link ?? '',
            'warehouses' => ProductWarehouseResource::collection($this->whenLoaded('warehouses')),
            'in_stock_warehouse' => ProductWarehouseResource::make($this->whenLoaded('inStockWarehouse')),
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->pluck('name')->toArray();
            }),
            'users_subscription_count' => $this->whenLoaded('usersSubscription', function () {
                return $this->usersSubscription->count();
            }),
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}

