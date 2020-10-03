<?php

namespace Modules\Settings\Transformers\Frontend\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ApplicationNavigationStructureResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [];
    }

    public static function getKey($resource){
        $key = null;
        if ($resource->relationLoaded('applicationNavigationStructure')){
            $key = isset($resource->applicationNavigationStructure['key']) ? $resource->applicationNavigationStructure['key'] : null;
        }
        return $key;
    }

}
