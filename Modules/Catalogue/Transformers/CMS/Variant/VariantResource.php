<?php

namespace Modules\Catalogue\Transformers\CMS\Variant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\VariantValue\VariantValueResource;

class VariantResource extends Resource
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
            'languages' => VariantNamesResource::collection($this->languages),
            'values' => VariantValueResource::collection($this->whenLoaded('values')),
            'is_color' => (boolean)  $this->is_color,
            'is_active' => (boolean)$this->is_active,
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
