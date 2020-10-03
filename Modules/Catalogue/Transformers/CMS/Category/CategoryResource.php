<?php

namespace Modules\Catalogue\Transformers\CMS\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
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
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'languages' => CategoryNamesResource::collection($this->languages),
            'code' => $this->code,
            'is_active' => (boolean)$this->is_active,
            'icon'  => GalleryResource::singleImage($this->whenLoaded('categoryIcon')) ,
            'image'  => GalleryResource::singleImage($this->whenLoaded('categoryImg')) ,
            'parent_category' => CategoryResource::make($this->parent),
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
