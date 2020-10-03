<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\WarehouseResource;

class AdminWarehouseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return WarehouseResource::collection($this->whenLoaded('warehouses'));
    }
}
