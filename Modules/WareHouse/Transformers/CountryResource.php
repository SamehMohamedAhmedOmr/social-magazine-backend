<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

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
        return [
            'id' => $this->id ,
            'country_code' => $this->country_code,
            'name' => LanguageFacade::loadCurrentLanguage($this),

            'image'  => getImagePath('flags', $this->image) ,
            'is_active' => $this->is_active,
            'languages' => CountryLanguageResource::collection($this->whenLoaded('language')) ,
        ];
    }
}
