<?php

namespace Modules\Settings\Transformers\Frontend\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Catalogue\Entities\Category;

class FrontendMenuResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $name = LanguageFacade::loadCurrentLanguage($this);

        $resource = [
            'key' => $this->key,
            'type' => $this->navigationMenu->key,
        ];

        if($name){
            $resource['name'] = $name;
        }
        if ($this->navigationMenu->key == 'CATEGORIES'){
            $categories = Category::all();
            $categories->load([
                'currentLanguage' , 'child.currentLanguage'
            ]);
            $resource['options'] = CategoryOptionsResource::collection($categories);
        }

        return $resource;
    }
}
