<?php

namespace Modules\Gallery\Transformers\Frontend;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Gallery\Facades\GalleryHelper;

class GalleryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return UrlGenerator|string
     */
    public function toArray($request)
    {
        $folder = $this->galleryType->folder;
        $folder = GalleryHelper::projectSlug().'/'.$folder;

        return GalleryHelper::getImagePath($folder, $this->image);
    }

    public static function singleImage($resource){
        if($resource){
            $folder = $resource->galleryType->folder;
            $folder = GalleryHelper::projectSlug().'/'.$folder;
            return GalleryHelper::getImagePath($folder, $resource->image);
        }
        return null;
    }

}
