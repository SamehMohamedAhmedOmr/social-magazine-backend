<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed iso
 * @property mixed pivot
 */
class DayLanguageResource extends Resource
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
            "lang" => $this->iso ,
            "name" => $this->pivot->name ,
        ];
    }
}
