<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\MagazineCategoryRequest;
use Modules\Sections\Services\CMS\MagazineCategoryService;
use Throwable;

class MagazineCategoryController extends Controller
{
    private $service;

    public function __construct(MagazineCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param MagazineCategoryRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(MagazineCategoryRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param MagazineCategoryRequest $request
     * @return JsonResponse|void
     */
    public function show(MagazineCategoryRequest $request)
    {
        return $this->service->show($request->magazine_category);
    }

    /**
     * Update the specified resource in storage.
     * @param MagazineCategoryRequest $request
     * @return JsonResponse|void
     */
    public function update(MagazineCategoryRequest $request)
    {
        return $this->service->update($request->magazine_category, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param MagazineCategoryRequest $request
     * @return JsonResponse|void
     */
    public function destroy(MagazineCategoryRequest $request)
    {
        return $this->service->delete($request->magazine_category);
    }
}
