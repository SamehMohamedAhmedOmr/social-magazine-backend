<?php

namespace Modules\FrontendUtilities\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\FrontendUtilities\Http\Requests\BannerRequest;
use Modules\FrontendUtilities\Services\CMS\BannerService;

class BannerController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->bannerService->index();
    }

    /**
     * Display a one resource.
     * @param BannerRequest $request
     * @param $id
     * @return void
     */
    public function show(BannerRequest $request, $id)
    {
        return $this->bannerService->show($id);
    }

    /**
     * Store a newly created resource in storage.
     * @param BannerRequest $request
     * @return void
     */
    public function store(BannerRequest $request)
    {
        return $this->bannerService->store($request);
    }

    /**
     * Update the specified resource in storage.
     * @param BannerRequest $request
     * @param int $id
     * @return void
     */
    public function update(BannerRequest $request, $id)
    {
        return $this->bannerService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param BannerRequest $request
     * @return JsonResponse
     */
    public function destroy(BannerRequest $request)
    {
        return $this->bannerService->delete($request->banner);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        return $this->bannerService->restore($request->banner_id);
    }

    public function export(PaginationRequest $request)
    {
        return $this->bannerService->export();
    }
}
