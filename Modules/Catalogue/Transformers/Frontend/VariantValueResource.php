<?php

namespace Modules\Catalogue\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class VariantValueResource extends Resource
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
            'id' =>  $this->id,
            'name' =>  isset($this->name)
                ? $this->name
                : (isset($this->currentLanguage) && isset($this->currentLanguage->name) ? $this->currentLanguage->name : '' ),
            'code' => $this->code,
            'value' => $this->value,
            'palette_image' => isset($this->palette_image) ? url(\Storage::url($this->palette_image)) : '',
        ];
    }
}
