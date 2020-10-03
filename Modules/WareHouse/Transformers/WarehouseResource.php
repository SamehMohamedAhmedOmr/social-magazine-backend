<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

class WarehouseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'warehouse_id' => $this->id,
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'warehouse_code' => $this->warehouse_code,
            'default_warehouse' => $this->default_warehouse,
            'is_active' => $this->is_active,
            'language' => WarehouseLanguageResource::collection($this->whenLoaded('language')),
            'districts' => DistrictResource::collection($this->whenLoaded('district')),
            'country' => CountryResource::make($this->whenLoaded('country')),
        ];
    }
}
