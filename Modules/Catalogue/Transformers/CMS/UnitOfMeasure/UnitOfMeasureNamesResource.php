<?php

namespace Modules\Catalogue\Transformers\CMS\UnitOfMeasure;

use Illuminate\Http\Resources\Json\Resource;

class UnitOfMeasureNamesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'lang' => $this->language->iso
        ];
    }
}
