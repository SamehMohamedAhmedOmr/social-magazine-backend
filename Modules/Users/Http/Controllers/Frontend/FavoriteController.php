<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\FavoriteRequest;
use Modules\Users\Services\Frontend\FavoriteService;

class FavoriteController extends Controller
{
    private $favoriteService;

    public function __construct(FavoriteService $favorite)
    {
        $this->favoriteService = $favorite;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->favoriteService->all();
    }

    /**
     * Store a newly created resource in storage.
     * @param FavoriteRequest $request
     * @return void
     */
    public function store(FavoriteRequest $request)
    {
        return $this->favoriteService->store();
    }

    /**
     * Remove the specified resource from storage.
     * @param FavoriteRequest $request
     * @return void
     */
    public function destroy(FavoriteRequest $request)
    {
        return $this->favoriteService->delete($request->favorite);
    }
}
