<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

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
            'main_font' => $this->main_font,
            'bold_font' => $this->bold_font,
            'regular_font' =>  $this->regular_font,
            'italic_font'  => $this->italic_font,
        ];
    }
}
