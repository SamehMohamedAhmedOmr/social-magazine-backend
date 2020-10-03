<?php

namespace Modules\Catalogue\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class CategoryResource extends Resource
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
            'id' => $this->id,
            'name' => $this->name ?? $this->currentLanguage->name,
            'slug' => $this->slug ?? $this->currentLanguage->slug,
            'parent' => CategoryResource::make($this->whenLoaded('parent')),
            'sub_categories' => CategoryResource::collection($this->whenLoaded('child')),
            'icon'  => GalleryResource::singleImage($this->whenLoaded('categoryIcon')) ,
            'image'  => GalleryResource::singleImage($this->whenLoaded('categoryImg')) ,
        ];
    }
}
