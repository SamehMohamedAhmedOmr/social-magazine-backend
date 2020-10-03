<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

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
        return [
            'id' => $this->id,
            'note_body' => LanguageFacade::loadCurrentLanguage($this, 'note_body'),
            'note_key' => $this->note_key,
            'language' => SystemNotesLanguageResource::collection($this->whenLoaded('language'))
        ];
    }
}
