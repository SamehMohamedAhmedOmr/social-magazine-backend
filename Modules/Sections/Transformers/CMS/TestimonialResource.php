<?php

namespace Modules\Sections\Transformers\CMS;

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
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'stars' => $this->stars,
            'image_id' => $this->image_id ,
            'image'  => GalleryResource::singleImage($this->whenLoaded('image')) ,
            'is_active' => $this->is_active,
        ];
    }
}
