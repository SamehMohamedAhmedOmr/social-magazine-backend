<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed pivot
 * @property mixed languages
 */
class TimeSectionDaysResource extends Resource
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
            'day_id' => $this->pivot->day_id,
            'day_details' => DayLanguageResource::collection($this->languages)
        ];
    }
}
