<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed languages
 * @property mixed is_active
 * @property mixed to
 * @property mixed from
 * @property mixed id
 * @property mixed days
 */
class TimeSectionResource extends Resource
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
            'id' => $this->id ,
            'from' => $this->from ,
            'to' => $this->to ,
            'is_active' =>  $this->is_active ,
            'languages' => TimeSectionLanguageResource::collection($this->language),
            'days' => TimeSectionDaysResource::collection($this->days)
        ];
    }
}
