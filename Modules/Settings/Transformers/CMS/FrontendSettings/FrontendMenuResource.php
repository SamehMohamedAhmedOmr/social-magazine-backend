<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

class FrontendMenuResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $name = LanguageFacade::loadCurrentLanguage($this);

        $resource = [
            'id' => $this->id,
            'key' => $this->key,
            'type' => FrontendMenuNavigationTypeResource::make($this->whenLoaded('navigationMenu')),
            'order' => $this->order,
            'languages' => FrontendMenuLanguagesResource::collection($this->whenLoaded('languages')),
        ];

        if($name){
            $resource['name'] = $name;
        }
        return $resource;
    }
}
