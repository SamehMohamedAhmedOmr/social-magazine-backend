<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\MagazineGoalsRequest;
use Modules\Sections\Services\CMS\MagazineGoalsService;
use Throwable;

class MagazineGoalsController extends Controller
{
    private $magazineGoalsService;

    public function __construct(MagazineGoalsService $magazineGoalsService)
    {
        $this->magazineGoalsService = $magazineGoalsService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->magazineGoalsService->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param MagazineGoalsRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(MagazineGoalsRequest $request)
    {
        return $this->magazineGoalsService->store($request);
    }

    /**
     * Show the specified resource.
     * @param MagazineGoalsRequest $request
     * @return JsonResponse|void
     */
    public function show(MagazineGoalsRequest $request)
    {
        return $this->magazineGoalsService->show($request->magazine_goal);
    }

    /**
     * Update the specified resource in storage.
     * @param MagazineGoalsRequest $request
     * @return JsonResponse|void
     */
    public function update(MagazineGoalsRequest $request)
    {
        return $this->magazineGoalsService->update($request->magazine_goal, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param MagazineGoalsRequest $request
     * @return JsonResponse|void
     */
    public function destroy(MagazineGoalsRequest $request)
    {
        return $this->magazineGoalsService->delete($request->magazine_goal);
    }
}
