<?php

namespace Modules\WareHouse\Transformers\Frontend\District;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\WareHouse\Transformers\DistrictLanguageResource;

/**
 * @property mixed languages
 * @property mixed country
 * @property mixed is_active
 * @property mixed shipping_role_id
 * @property mixed warehouse_id
 * @property mixed id
 * @property mixed country_id
 */
class DistrictChildResource extends Resource
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
            'is_active' => $this->is_active,
            'parent_id' => $this->parent_id,
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'sub_districts' => DistrictChildResource::collection($this->whenLoaded('child')),
        ];
    }
}
