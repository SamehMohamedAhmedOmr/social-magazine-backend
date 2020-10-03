<?php

namespace Modules\Catalogue\Transformers\CMS\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ProductNamesResource extends Resource
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
            'description' => $this->description,
            'lang' => $this->language->iso
        ];
    }
}
