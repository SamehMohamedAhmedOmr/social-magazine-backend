<?php

namespace Modules\Settings\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Entities\SystemNoteLanguage;

class SystemNotesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $languages = $this->whenLoaded('language');
        $note_body = $this->prepareLanguages($languages);
        return [
            'note_key' => $this->note_key,
            'note_body' => $note_body
        ];
    }

    private function prepareLanguages($languages)
    {
        if (!$languages) {
            return null;
        }
        return $languages->where('id', \Session::get('language_id'))->pluck('pivot.note_body')->first();
    }
}
