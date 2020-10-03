<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\CountryResource;
use Modules\WareHouse\Transformers\DistrictResource;
use Modules\WareHouse\Transformers\Frontend\District\DistrictTreeResource;

class AddressResource extends Resource
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
            'title' => $this->title,
            'district' => DistrictTreeResource::make($this->whenLoaded('district')),
            'street' => $this->street,
            'nearest_landmark' => $this->nearest_landmark,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'address_phone' => $this->address_phone,
            'building_no' => $this->building_no,
            'floor_no' => $this->floor_no,
            'apartment_no' => $this->apartment_no,

            'user_id' => $this->user_id,
            'is_active' => $this->is_active,

            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }
}
