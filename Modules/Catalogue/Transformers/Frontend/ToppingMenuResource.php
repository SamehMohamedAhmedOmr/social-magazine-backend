<?php

namespace Modules\Catalogue\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

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
            'name' =>  $this->name ?? $this->currentLanguage->name,
            'slug' => $this->slug ?? $this->currentLanguage->slug,
            'toppings' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
