<?php

namespace Modules\FrontendUtilities\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\FrontendUtilities\Services\Frontend\CollectionServiceFront;

class CollectionController extends Controller
{
    private $collectionService;

    public function __construct(CollectionServiceFront $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->collectionService->index();
    }
}
