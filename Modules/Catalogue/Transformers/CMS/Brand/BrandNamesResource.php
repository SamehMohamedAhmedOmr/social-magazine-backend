<?php

namespace Modules\Catalogue\Transformers\CMS\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class BrandNamesResource extends Resource
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
            'name' => $this->name,
            'lang' => $this->language->iso
        ];
    }
}
