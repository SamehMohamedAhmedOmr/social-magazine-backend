<?php

namespace Modules\WareHouse\Transformers\Frontend\District;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\WareHouse\Transformers\DistrictLanguageResource;

/**
 * @property mixed shipping_role_id
 * @property mixed warehouse_id
 * @property mixed languages
 * @property mixed id
 */
class DistrictTreeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $parent_id = isset($this->parent_id) ? $this->parent_id : 0;
        return [
            'id' => $this->id ,
            'is_active' => $this->is_active,
            'parent_id' => $parent_id,
            'parent' => DistrictTreeResource::make($this->whenLoaded('parentDistrict')),

            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'zoom_level' => $this->zoom_level,

            'name' => LanguageFacade::loadCurrentLanguage($this),
            'sub_districts' => DistrictChildResource::collection($this->whenLoaded('child')),
        ];
    }


}
