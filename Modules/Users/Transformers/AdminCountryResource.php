<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\CountryResource;

class AdminCountryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return CountryResource::collection($this->whenLoaded('countries'));
    }
}
