<?php

namespace Modules\Sections\Transformers\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class TestimonialResource extends Resource
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
            'content' => $this->content,
            'stars' => $this->stars,
            'image'  => GalleryResource::singleImage($this->whenLoaded('image')) ,
        ];
    }
}
