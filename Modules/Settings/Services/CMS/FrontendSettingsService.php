<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\FrontendSettings\FrontendColorsRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendSettingsRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendSocialMediaRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendTypographyRepository;
use Modules\Settings\Transformers\CMS\FrontendSettings\FrontendSettingsResource;
use Throwable;

class FrontendSettingsService extends LaravelServiceClass
{
    private $frontend_settings_repository,
        $frontend_colors_repository,
        $frontend_typography_repository,
        $frontend_social_media_repository;

    public function __construct(FrontendSettingsRepository $frontend_settings_repository,
                                FrontendColorsRepository $frontend_colors_repository,
                                FrontendTypographyRepository $frontend_typography_repository,
                                FrontendSocialMediaRepository $frontend_social_media_repository)
    {
        $this->frontend_settings_repository = $frontend_settings_repository;
        $this->frontend_colors_repository = $frontend_colors_repository;
        $this->frontend_social_media_repository = $frontend_social_media_repository;
        $this->frontend_typography_repository = $frontend_typography_repository;
    }

    public function show($id = null)
    {
        $frontend_settings = $this->frontend_settings_repository->get(\Session::get('country_id'), [], 'country_id');

        if ($frontend_settings){
            $frontend_settings->load($this->frontend_settings_repository->relationships([
                'languages',
                'menu.languages'
            ]));
            $frontend_settings = FrontendSettingsResource::make($frontend_settings);
        }

        return ApiResponse::format(200, $frontend_settings);
    }

    /**
     * Handles Store Setting
     *
     * @param $request
     * @return mixed
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $setting_data = collect([]);

            $setting_data = $this->prepareKeys($request, $setting_data, 'favicon');
            $setting_data = $this->prepareKeys($request, $setting_data, 'logo');
            $setting_data = $this->prepareKeys($request, $setting_data, 'social_sharing_img');
            $setting_data = $this->prepareKeys($request, $setting_data, 'google_analytics_id');
            $setting_data = $this->prepareKeys($request, $setting_data, 'facebook_pixel_id');
            $setting_data = $this->prepareKeys($request, $setting_data, 'enable_recaptcha');
            $setting_data = $this->prepareKeys($request, $setting_data, 'app_nav_structure_id');
            $setting_data->put('country_id', \Session::get('country_id'));

            $setting_data = $setting_data->toArray();

            $frontend_settings = $this->frontend_settings_repository->updateOrCreate([], $setting_data);

            // store language
            if ($request->has('data')) {
                $languages = prepareObjectLanguages($request->data);
                $frontend_settings = $this->frontend_settings_repository->updateLanguage($frontend_settings, $languages);
            }

            // store colors
            if ($request->has('colors')) {
                $this->storeColors($frontend_settings, $request->colors);
            }

            // store typography
            if ($request->has('typography')) {
                $this->storeTypography($frontend_settings, $request->typography);
            }

            // store social_media
            if ($request->has('social_media')) {
                $this->storeSocialMedia($frontend_settings, $request->social_media);
            }


            $frontend_settings->load($this->frontend_settings_repository->relationships([
                'languages'
            ]));

            $frontend_settings = FrontendSettingsResource::make($frontend_settings);

            return ApiResponse::format(200, $frontend_settings, 'Frontend Settings updated successfully');
        });
    }

    private function prepareKeys($object, $keys, $key)
    {
        if (isset($object[$key])) {
            $keys->put($key, $object[$key]);
        }
        return $keys;
    }

    private function storeColors($frontend_settings, $colors)
    {

        $keys = collect([]);
        $keys = $this->prepareKeys($colors, $keys, 'main_color');
        $keys = $this->prepareKeys($colors, $keys, 'second_color');
        $keys = $this->prepareKeys($colors, $keys, 'third_color');

        $keys = $keys->toArray();

        return $this->frontend_colors_repository->updateOrCreate([
            'frontend_setting_id' => $frontend_settings->id
        ], $keys);
    }

    private function storeTypography($frontend_settings, $typography)
    {
        $keys = collect([]);
        $keys = $this->prepareKeys($typography, $keys, 'main_font');
        $keys = $this->prepareKeys($typography, $keys, 'bold_font');
        $keys = $this->prepareKeys($typography, $keys, 'regular_font');
        $keys = $this->prepareKeys($typography, $keys, 'italic_font');

        $keys = $keys->toArray();

        return $this->frontend_typography_repository->updateOrCreate([
            'frontend_setting_id' => $frontend_settings->id
        ], $keys);
    }

    private function storeSocialMedia($frontend_settings, $social_media)
    {
        $keys = collect([]);
        $keys = $this->prepareKeys($social_media, $keys, 'facebook');
        $keys = $this->prepareKeys($social_media, $keys, 'twitter');
        $keys = $this->prepareKeys($social_media, $keys, 'instagram');
        $keys = $this->prepareKeys($social_media, $keys, 'youtube');
        $keys = $this->prepareKeys($social_media, $keys, 'google_plus');
        $keys = $this->prepareKeys($social_media, $keys, 'pinterest');
        $keys = $this->prepareKeys($social_media, $keys, 'linkedin');

        $keys = $keys->toArray();

        return $this->frontend_social_media_repository->updateOrCreate([
            'frontend_setting_id' => $frontend_settings->id
        ], $keys);
    }
}
