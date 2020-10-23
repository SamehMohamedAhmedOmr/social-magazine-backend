<?php

namespace Modules\Sections\Transformers\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class TrackerResource extends Resource
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
            'visitors' => intval($this['visitors'])
        ];
    }
}
