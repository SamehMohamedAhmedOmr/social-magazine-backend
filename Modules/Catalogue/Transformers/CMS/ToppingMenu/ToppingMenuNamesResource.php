<?php

namespace Modules\Catalogue\Transformers\CMS\ToppingMenu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ToppingMenuNamesResource extends Resource
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
