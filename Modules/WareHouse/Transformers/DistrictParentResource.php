<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

/**
 * @property mixed languages
 * @property mixed country
 * @property mixed is_active
 * @property mixed shipping_role_id
 * @property mixed warehouse_id
 * @property mixed id
 * @property mixed country_id
 */
class DistrictParentResource extends Resource
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
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'is_active' => $this->is_active,
            'languages' => DistrictLanguageResource::collection($this->whenLoaded('language')),
        ];
    }
}
