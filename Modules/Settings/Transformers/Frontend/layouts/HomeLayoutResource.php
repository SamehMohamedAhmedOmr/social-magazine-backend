<?php

namespace Modules\Settings\Transformers\Frontend\layouts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class HomeLayoutResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $slider = $this->pagesLayout->where('key','slider')->first();
        $collection = $this->pagesLayout->where('key','collection')->first();
        $categories = $this->pagesLayout->where('key','categories')->first();

        return [
            'slider' => $slider->value,
            'collection' => $collection->value,
            'categories' => $categories->value,
        ];
    }
}
