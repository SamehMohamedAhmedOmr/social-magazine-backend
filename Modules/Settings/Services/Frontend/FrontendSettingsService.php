<?php

namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Settings\Repositories\FrontendSettings\FrontendSettingsRepository;
use Modules\Settings\Repositories\layouts\PageLayoutTypeRepository;
use Modules\Settings\Transformers\Frontend\FrontendSettings\FrontendSettingsResource;

class FrontendSettingsService
{
    private $frontend_settings_repository, $page_layout_type_repository;

    public function __construct(FrontendSettingsRepository $frontend_settings_repository,
                                PageLayoutTypeRepository $page_layout_type_repository)
    {
        $this->frontend_settings_repository = $frontend_settings_repository;
        $this->page_layout_type_repository = $page_layout_type_repository;
    }

    public function show()
    {
        $frontend_settings = $this->frontend_settings_repository->get(\Session::get('country_id'), [], 'country_id');

        if ($frontend_settings) {
            $frontend_settings->load($this->frontend_settings_repository->relationships([
                'currentLanguage',
                'menu.currentLanguage',
                'applicationNavigationStructure'
            ]));

            $page_layout_types = $this->page_layout_type_repository->all([], ['PagesLayout']);

            $home_layout = $page_layout_types->where('key', 'home_layout')->first();
            $products_layout = $page_layout_types->where('key', 'products_layout')->first();
            $product_card_layout = $page_layout_types->where('key', 'product_card_layout')->first();


            $frontend_settings = new FrontendSettingsResource($frontend_settings, $home_layout,
                $products_layout, $product_card_layout);
        }


        return ApiResponse::format(200, $frontend_settings);
    }

}
