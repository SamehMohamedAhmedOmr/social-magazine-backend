<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class FrontendColorResource extends Resource
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
            'main_color' => $this->main_color,
            'second_color' => $this->second_color,
            'third_color' => $this->third_color,
        ];
    }
}
