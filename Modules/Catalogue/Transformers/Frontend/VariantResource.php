<?php

namespace Modules\Catalogue\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

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
            'name' =>  $this->name ?? $this->currentLanguage->name,
            'values' => VariantValueResource::collection($this->values),
            'is_color' => (boolean)$this->is_color,
        ];
    }
}
