<?php

namespace Modules\Settings\Transformers\Frontend\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Transformers\Frontend\FontsResource;

class FrontendTypographyResource extends Resource
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
            'main_font' => $this->loaded('mainFont'),
            'bold_font' => $this->loaded('boldFont'),
            'regular_font' =>  $this->loaded('regularFont'),
            'italic_font'  => $this->loaded('italicFont'),
        ];
    }

    private function loaded($relation){
        return ($this->relationLoaded($relation)) ? FontsResource::getFile($this->whenLoaded($relation)) : null;
    }
}
