<?php

namespace Modules\FrontendUtilities\Transformers\CMS\Promocode;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\Brand\BrandNamesResource;

class PromocodeBrandResource extends Resource
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
            'languages' => BrandNamesResource::collection($this->whenLoaded('languages')),
            'is_active' => (boolean)$this->is_active,
        ];
    }
}
