<?php

namespace Modules\FrontendUtilities\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed description
 * @property mixed title
 * @property mixed lang_id
 * @property mixed subject_1
 * @property mixed alternative
 * @property mixed subject_2
 * @property mixed language
 * @property mixed id
 * @property mixed iso
 * @property mixed pivot
 */
class BannerLanguageResource extends Resource
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
            'banner_lang_id' => $this->id ,
            'lang' => $this->iso ,
            'title' => $this->pivot->title,
            'description' => $this->pivot->description,
            "alternative" => $this->pivot->alternative,
            "first_subject" => $this->pivot->subject_1,
            "second_subject" => $this->pivot->subject_2,
        ];
    }
}
