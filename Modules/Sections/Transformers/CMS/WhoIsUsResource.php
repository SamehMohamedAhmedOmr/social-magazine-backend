<?php

namespace Modules\Sections\Transformers\CMS;

use Illuminate\Http\Resources\Json\Resource;

class WhoIsUsResource extends Resource
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
