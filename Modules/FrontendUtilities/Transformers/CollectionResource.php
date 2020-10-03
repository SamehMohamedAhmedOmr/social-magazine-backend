<?php

namespace Modules\FrontendUtilities\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Transformers\CMS\Product\ProductResource;

/**
 * @property mixed languages
 * @property mixed enable_android
 * @property mixed enable_ios
 * @property mixed enable_web
 * @property mixed id
 * @property mixed is_active
 * @property mixed collectionLanguages
 * @property mixed order
 */
class CollectionResource extends Resource
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
            'id' => $this->id ,
            'name' => LanguageFacade::loadCurrentLanguage($this,'title'),
            'enable_ios'  => $this->enable_ios ,
            'enable_android'  => $this->enable_android ,
            'enable_web'  => $this->enable_web ,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'languages' => CollectionLanguageResource::collection($this->whenLoaded('language')),
            'products' => ProductResource::collection($this->whenLoaded('products')) ,
        ];
    }
}
