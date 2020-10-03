<?php

namespace Modules\Catalogue\Transformers\CMS\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class BrandResource extends Resource
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
            'languages' => BrandNamesResource::collection($this->languages),
            'is_active' => (boolean)$this->is_active,
            'icon'  => GalleryResource::singleImage($this->whenLoaded('brandImg')) ,
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
