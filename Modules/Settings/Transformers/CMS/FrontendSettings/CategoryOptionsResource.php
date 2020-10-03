<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CategoryOptionsResource extends Resource
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
            'name' => isset($this->currentLanguage->name) ? $this->currentLanguage->name : null,
            'slug' => isset($this->currentLanguage->slug) ? $this->currentLanguage->slug : null,
            'parent' => CategoryOptionsResource::make($this->whenLoaded('parent')),
            'options' => CategoryOptionsResource::collection($this->whenLoaded('child')),
        ];
    }
}
