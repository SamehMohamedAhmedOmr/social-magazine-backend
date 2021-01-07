<?php

namespace Modules\Sections\Transformers\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class EventsResource extends Resource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'images' => GalleryResource::collection($this->whenLoaded('images')),
            'date' => $this->date
        ];
    }
}
