<?php

namespace Modules\Settings\Transformers\CMS\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Gallery\Transformers\Frontend\GalleryResource;

class FrontendSettingsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $home_page_title = LanguageFacade::loadCurrentLanguage($this, 'home_page_title');
        $home_page_meta_desc = LanguageFacade::loadCurrentLanguage($this, 'home_page_meta_desc');;
        $resource = [
            'social_sharing_img' => GalleryResource::singleImage($this->whenLoaded('socialSharingImg')),
            'logo' => GalleryResource::singleImage($this->whenLoaded('logoImg')),
            'fav_icon' => GalleryResource::singleImage($this->whenLoaded('faviconImg')),

            'facebook_pixel_id' => $this->facebook_pixel_id,
            'google_analytics_id' => $this->google_analytics_id,
            'enable_recaptcha' => $this->enable_recaptcha,

            'app_nav_structure_id' => $this->app_nav_structure_id,

            'languages' => FrontendSettingLanguagesResource::collection($this->whenLoaded('languages')),
            'colors' => FrontendColorResource::make($this->whenLoaded('colors')),
            'typography' => FrontendTypographyResource::make($this->whenLoaded('typography')),
            'social_media' => FrontendSocialMediaResource::make($this->whenLoaded('socialMedia')),
        ];

        if($home_page_title){
            $resource['home_page_title'] = $home_page_title;
        }

        if($home_page_meta_desc){
            $resource['home_page_meta_desc'] = $home_page_meta_desc;
        }

        return $resource;
    }
}
