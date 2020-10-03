<?php

namespace Modules\Base\ResponseShape;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ExportResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'link' => $this['path'],
        ];
    }
}
