<?php

namespace Modules\Catalogue\Transformers\CMS\Category;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;

class CategoryNamesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
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
