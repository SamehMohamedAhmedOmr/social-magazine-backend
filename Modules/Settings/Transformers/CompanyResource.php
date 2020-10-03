<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class CompanyResource extends Resource
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
            'logo'  => GalleryResource::singleImage($this->whenLoaded('companyImg')) ,
            'language' => CompanyLanguagesResource::collection($this->whenLoaded('language'))
        ];
    }
}
