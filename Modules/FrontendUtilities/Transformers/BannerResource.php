<?php

namespace Modules\FrontendUtilities\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

/**
 * @property mixed link
 * @property mixed languages
 * @property mixed image
 * @property mixed enable_os
 * @property mixed enable_android
 * @property mixed enable_web
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed id
 * @property mixed order
 * @property mixed Languages
 * @property mixed is_active
 */
class BannerResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $title = LanguageFacade::loadCurrentLanguage($this,'title');
        $description = LanguageFacade::loadCurrentLanguage($this,'description');
        $alternative = LanguageFacade::loadCurrentLanguage($this,'alternative');
        $first_subject = LanguageFacade::loadCurrentLanguage($this,'subject_1');
        $second_subject = LanguageFacade::loadCurrentLanguage($this,'subject_2');

        $resource =  [
            'id' => $this->id ,
            'link'  => $this->link ,
            'image'  => GalleryResource::singleImage($this->whenLoaded('bannerImg')) ,
            'enable_ios'  => $this->enable_ios ,
            'enable_android'  => $this->enable_android ,
            'enable_web'  => $this->enable_web ,
            'order' => $this->order,
            'is_active' => $this->is_active ,
            'languages' => BannerLanguageResource::collection($this->whenLoaded('language')) ,
        ];

        if ($title || $description || $alternative || $first_subject || $second_subject){
            $resource['title'] = $title;
            $resource['description'] = $description;
            $resource['alternative'] = $alternative;
            $resource['first_subject'] = $first_subject;
            $resource['second_subject'] = $second_subject;
        }

        return $resource;
    }
}
