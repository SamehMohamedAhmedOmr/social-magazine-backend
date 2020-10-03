<?php

namespace Modules\WareHouse\Transformers\CMS\Shipment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CompanyResource extends Resource
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
            'key' => $this->key,
            'name' => $this->name,
            'is_active' => (boolean) $this->is_active,
        ];
    }
}
