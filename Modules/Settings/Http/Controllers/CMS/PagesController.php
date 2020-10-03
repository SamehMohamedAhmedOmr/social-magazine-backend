<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\PageRequest;
use Modules\Settings\Services\CMS\PagesService;

class PagesController extends Controller
{
    private $pages_service;

    public function __construct(PagesService $pages_service)
    {
        $this->pages_service = $pages_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->pages_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PageRequest $request
     * @return JsonResponse|void
     */
    public function store(PageRequest $request)
    {
        return $this->pages_service->store($request);
    }

    /**
     * Show the specified resource.
     * @param PageRequest $request
     * @return JsonResponse|void
     */
    public function show(PageRequest $request)
    {
        return $this->pages_service->show($request->page);
    }

    /**
     * Update the specified resource in storage.
     * @param PageRequest $request
     * @return JsonResponse|void
     */
    public function update(PageRequest $request)
    {
        return $this->pages_service->update($request->page, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param PageRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PageRequest $request)
    {
        return $this->pages_service->delete($request->page);
    }

    public function export(PaginationRequest $request)
    {
        return $this->pages_service->export();
    }
}
