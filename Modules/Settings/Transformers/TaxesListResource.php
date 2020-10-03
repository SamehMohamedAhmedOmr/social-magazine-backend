<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\WareHouse\Transformers\CountryResource;

class TaxesListResource extends Resource
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
            'name' => LanguageFacade::loadCurrentLanguage($this),

            'key' => $this->key,
            'price' => $this->price,
            'is_active' => $this->is_active,

            'country' => CountryResource::make($this->whenLoaded('country')),
            'amount_type' => TaxesAmountTypeResource::make($this->whenLoaded('amountType')),
            'tax_type' => TaxesTypeResource::make($this->whenLoaded('taxType')),
            'languages' => TaxesListLanguagesResource::collection($this->whenLoaded('language'))
        ];
    }
}
