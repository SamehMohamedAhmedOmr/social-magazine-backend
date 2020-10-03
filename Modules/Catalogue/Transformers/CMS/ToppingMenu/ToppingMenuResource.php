<?php

namespace Modules\Catalogue\Transformers\CMS\ToppingMenu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\Product\ProductResource;
use Modules\Catalogue\Transformers\CMS\VariantValue\VariantValueResource;

class ToppingMenuResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'languages' => ToppingMenuNamesResource::collection($this->languages),
            'toppings' => ProductResource::collection($this->whenLoaded('products')),
            'is_active' => (boolean)$this->is_active,
            'number_of_toppings' => $this->products()->count() ,
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
