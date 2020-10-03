<?php

namespace Modules\Settings\Transformers\Frontend\FrontendSettings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;
use Modules\Gallery\Transformers\Frontend\GalleryResource;
use Modules\Settings\Transformers\Frontend\layouts\HomeLayoutResource;
use Modules\Settings\Transformers\Frontend\layouts\ProductCardLayoutResource;
use Modules\Settings\Transformers\Frontend\layouts\ProductLayoutResource;

class FrontendSettingsResource extends Resource
{
    private $home_layout ,$products_layout, $product_card_layout;
    public function __construct($resource, $home_layout ,$products_layout, $product_card_layout)
    {
        $this->home_layout = $home_layout;
        $this->products_layout = $products_layout;
        $this->product_card_layout = $product_card_layout;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $home_page_title = LanguageFacade::loadCurrentLanguage($this, 'home_page_title');
        $home_page_meta_desc = LanguageFacade::loadCurrentLanguage($this, 'home_page_meta_desc');

        $resource = [
            'social_sharing_img' => GalleryResource::singleImage($this->whenLoaded('socialSharingImg')),
            'logo' => GalleryResource::singleImage($this->whenLoaded('logoImg')),
            'fav_icon' => GalleryResource::singleImage($this->whenLoaded('faviconImg')),

            'app_nav_structure' => ApplicationNavigationStructureResource::getKey($this),

            'facebook_pixel_id' => $this->facebook_pixel_id,
            'google_analytics_id' => $this->google_analytics_id,
            'enable_recaptcha' => $this->enable_recaptcha,
            'colors' => FrontendColorResource::make($this->whenLoaded('colors')),
            'typography' => FrontendTypographyResource::make($this->whenLoaded('typography')),
            'social_media' => FrontendSocialMediaResource::make($this->whenLoaded('socialMedia')),
            'menu' => FrontendMenuResource::collection($this->whenLoaded('menu')),

            'home_layout' => HomeLayoutResource::make($this->home_layout),
            'products_layout' => ProductLayoutResource::make($this->products_layout),
            'product_card_layout' => ProductCardLayoutResource::make($this->product_card_layout),
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
