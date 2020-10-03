<?php

namespace Modules\Catalogue\Transformers\CMS\VariantValue;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\Variant\VariantResource;

class VariantValueResource extends Resource
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
            'id' => $this->id,
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'code' => $this->code,
            'languages' => VariantValueNamesResource::collection($this->languages),
            'value' => $this->value,
            'palette_image' => isset($this->palette_image) ? url(\Storage::url($this->palette_image)) : '',
            'variant' => VariantResource::make($this->whenLoaded('variant')),
            'is_active' => (boolean)$this->is_active,
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
