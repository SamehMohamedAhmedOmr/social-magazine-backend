<?php

namespace Modules\Settings\Http\Controllers\CMS\FrontendSettings;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\FontsRequest;
use Modules\Settings\Http\Requests\FrontendSettings\FrontendSettingsRequest;
use Modules\Settings\Services\CMS\FrontendFontsService;
use Modules\Settings\Services\CMS\FrontendSettingsService;

class FrontendFontsController extends Controller
{

    private $frontend_fonts_service;

    public function __construct(FrontendFontsService $frontend_fonts_service)
    {
        $this->frontend_fonts_service = $frontend_fonts_service;
    }

    /**
     * Show the specified resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->frontend_fonts_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param FontsRequest $request
     * @return void
     */
    public function store(FontsRequest $request)
    {
        return $this->frontend_fonts_service->store($request);
    }



}
