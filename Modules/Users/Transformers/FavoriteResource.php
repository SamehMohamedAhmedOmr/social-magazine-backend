<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\Frontend\ProductResource;

class FavoriteResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return ProductResource
     */
    public function toArray($request)
    {
        return ProductResource::make($this->whenLoaded('product'));
    }
}
