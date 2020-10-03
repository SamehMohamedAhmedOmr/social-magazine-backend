<?php

namespace Modules\WareHouse\Transformers\Frontend\country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\Frontend\District\DistrictTreeResource;

/**
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed is_active
 * @property mixed country_code
 * @property mixed id
 * @property mixed countryLanguages
 * @property mixed languages
 * @property mixed image
 */
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
        $languages = $this->whenLoaded('language');
        $name = $this->prepareLanguages($languages);

        return [
            'id' => $this->id ,
            'name' => $name,
            'country_code' => $this->country_code,
            'image'  => getImagePath('flags', $this->image),
            'districts' => DistrictTreeResource::collection($this->whenLoaded('district'))
        ];
    }

    private function prepareLanguages($languages)
    {
        if (!$languages) {
            return null;
        }
        return $languages->where('id', \Session::get('language_id'))->pluck('pivot.name')->first();
    }
}
