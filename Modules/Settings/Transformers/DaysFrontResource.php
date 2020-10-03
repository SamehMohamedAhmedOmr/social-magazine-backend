<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed days_languages
 * @property mixed timeSections
 * @property mixed daysLanguages
 * @property mixed id
 * @property mixed languages
 */
class DaysFrontResource extends Resource
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
            'day_id' => $this->id ,
            'day_languages' => DayLanguageResource::collection($this->languages) ,
            'timeSections' => TimeSectionFrontResource::collection($this->timeSections)
        ];
    }
}
