<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

class PageResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $title = LanguageFacade::loadCurrentLanguage($this, 'title');
        $content = LanguageFacade::loadCurrentLanguage($this, 'content');
        $seo_title = LanguageFacade::loadCurrentLanguage($this, 'seo_title');
        $seo_description = LanguageFacade::loadCurrentLanguage($this, 'seo_description');

        $resource = [
            'id' => $this->id,
            'page_url' => $this->page_url,
            'is_active' => $this->is_active,
            'languages' => PagesLanguagesResource::collection($this->whenLoaded('languages'))
        ];

        $resource = LanguageFacade::prepareLanguagesForResource($resource, $title, 'title');
        $resource = LanguageFacade::prepareLanguagesForResource($resource, $content, 'content');
        $resource = LanguageFacade::prepareLanguagesForResource($resource, $seo_title, 'seo_title');
        $resource = LanguageFacade::prepareLanguagesForResource($resource, $seo_description, 'seo_description');

        return  $resource;
    }

}
