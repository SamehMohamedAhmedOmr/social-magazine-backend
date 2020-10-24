<?php

namespace Modules\Sections\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Gallery\Transformers\CMS\GalleryResource;

class MagazineCategoryResource extends Resource
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
            'content' => $this->content,
            'images' => GalleryResource::collection($this->whenLoaded('images')),
            'is_active' => $this->is_active,
        ];
    }
}
