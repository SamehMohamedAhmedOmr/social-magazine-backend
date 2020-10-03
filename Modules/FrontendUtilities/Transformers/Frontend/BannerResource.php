<?php

namespace Modules\FrontendUtilities\Transformers\Frontend;

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
        $main_text = LanguageFacade::loadCurrentLanguage($this,'title');
        $first_subject = LanguageFacade::loadCurrentLanguage($this,'subject_1');
        $second_subject = LanguageFacade::loadCurrentLanguage($this,'subject_2');

        $resource = [
            'id' => $this->id,
            'image'  => GalleryResource::singleImage($this->whenLoaded('bannerImg')) ,
            'link' => $this->link,
            'order' => $this->order,
        ];

        if ($main_text || $first_subject || $second_subject){
            $resource['mainText'] = $main_text;
            $resource['upperText'] = $first_subject;
            $resource['lowerText'] = $second_subject;
        }

        return  $resource;
    }
}
