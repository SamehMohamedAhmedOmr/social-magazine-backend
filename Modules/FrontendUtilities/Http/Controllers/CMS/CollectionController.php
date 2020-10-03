<?php

namespace Modules\FrontendUtilities\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\FrontendUtilities\Http\Requests\CollectionRequest;
use Modules\FrontendUtilities\Services\CMS\CollectionService;

class CollectionController extends Controller
{
    private $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->collectionService->index();
    }

    /**
     * Display a one resource.
     * @param CollectionRequest $request
     * @return void
     */
    public function show(CollectionRequest $request)
    {
        return $this->collectionService->show($request->collection);
    }

    /**
     * Store a newly created resource in storage.
     * @param CollectionRequest $request
     * @return void
     */
    public function store(CollectionRequest $request)
    {
        return $this->collectionService->store($request);
    }

    /**
     * Update the specified resource in storage.
     * @param CollectionRequest $request
     * @param int $id
     * @return void
     */
    public function update(CollectionRequest $request, $id)
    {
        return $this->collectionService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param CollectionRequest $request
     * @return JsonResponse
     */

    public function destroy(CollectionRequest $request)
    {
        return $this->collectionService->delete($request->collection);
    }

    public function export(PaginationRequest $request)
    {
        return $this->collectionService->export();
    }

}
