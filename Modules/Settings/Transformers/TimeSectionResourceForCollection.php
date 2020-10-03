<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed id
 * @property mixed from
 * @property mixed to
 * @property mixed is_active
 * @property mixed timeSectionLanguages
 */
class TimeSectionResourceForCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ,
            'from' => $this->from ,
            'to' => $this->to ,
            'is_active' =>  $this->is_active ,
            'languages' => TimeSectionLanguageResource::collection($this->timeSectionLanguages),
        ];
    }
}
