<?php

namespace Modules\Basic\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CountryResource extends Resource
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
            'id' => $this->id ,
            'country_code' => $this->country_code,
            'name' => $this->name . ' جمهوريةِ',
            'image'  => getImagePath('flags', $this->image) ,
        ];
    }
}
