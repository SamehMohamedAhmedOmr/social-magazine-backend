<?php

namespace Modules\Sections\Transformers\Front;

use Illuminate\Http\Resources\Json\Resource;

class MagazineInformationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
