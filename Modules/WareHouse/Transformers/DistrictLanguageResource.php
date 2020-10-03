<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed language
 * @property mixed id
 * @property mixed pivot
 * @property mixed iso
 */
class DistrictLanguageResource extends Resource
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
            'lang' => $this->iso ,
            'name' => $this->pivot->name,
            'description' => $this->pivot->description,
        ];
    }
}
