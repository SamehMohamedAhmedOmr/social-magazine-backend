<?php

namespace Modules\FrontendUtilities\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\Frontend\ProductResource;

class CollectionsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->prepareLanguages();
        return [
            'id' => $this->id ,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'name' => $name,
            'products' => ProductResource::collection($this->products),
        ];
    }

    private function prepareLanguages()
    {
        $languages = $this->whenLoaded('language');

        if (!$this->relationLoaded('language')) {
            return null;
        }
        $language = $languages->where('id', \Session::get('language_id'))->first();
        if (!$language) {
            return null;
        }
        return $language->pivot->title;
    }
}
