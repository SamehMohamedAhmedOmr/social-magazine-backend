<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class FrontendSettingLanguagesResource extends Resource
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
            'home_page_title' => $this->pivot->home_page_title,
            'home_page_meta_desc' => $this->pivot->home_page_meta_desc
        ];
    }
}
