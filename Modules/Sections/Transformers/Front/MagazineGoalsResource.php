<?php

namespace Modules\Sections\Transformers\Front;

use Illuminate\Http\Resources\Json\Resource;

class MagazineGoalsResource extends Resource
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
