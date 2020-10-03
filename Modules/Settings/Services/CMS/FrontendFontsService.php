<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\FontsRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendColorsRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendMenuRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendSettingsRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendSocialMediaRepository;
use Modules\Settings\Repositories\FrontendSettings\FrontendTypographyRepository;
use Modules\Settings\Transformers\CompanyResource;
use Modules\Settings\Transformers\FontsResource;
use Modules\Settings\Transformers\FrontendSettings\FrontendSettingsResource;

class FrontendFontsService extends LaravelServiceClass
{
    private $fonts_repository;

    public function __construct(FontsRepository $fonts_repository)
    {
        $this->fonts_repository = $fonts_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($fonts, $pagination) = parent::paginate($this->fonts_repository, null, false);
        } else {
            $fonts = parent::list($this->fonts_repository, false);

            $pagination = null;
        }

        $fonts = FontsResource::collection($fonts);

        return ApiResponse::format(200, $fonts, [], $pagination);
    }

    public function store($request = null)
    {
        /* Upload Font */
        $file_name = $request->font_file->getClientOriginalName();
        \Storage::putFileAs('public/fonts',  $request->font_file, $file_name);


        $font = $this->fonts_repository->create([
            'font_name' => $request->name,
            'font_path' => $file_name,
            'type' => $request->type,
            'language_id' => $request->language_id,

        ]);

        $font = FontsResource::make($font);

        return ApiResponse::format(200, $font);
    }

}
