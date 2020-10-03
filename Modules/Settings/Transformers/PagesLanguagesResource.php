<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PagesLanguagesResource extends Resource
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
            'lang' => $this->iso,
            'title' => $this->pivot->title,
            'content' => $this->pivot->content,
            'seo_title' => $this->pivot->seo_title,
            'seo_description' => $this->pivot->seo_description,
        ];
    }
}
