<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\ACL\Transformers\RoleResource;

class AdminResource extends Resource
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
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'countries' => AdminCountryResource::make($this->whenLoaded('admin')),
            'warehouses' => AdminWarehouseResource::make($this->whenLoaded('admin')),
            'roles' => RoleResource::collection($this->whenLoaded('roles'))
        ];
    }
}
