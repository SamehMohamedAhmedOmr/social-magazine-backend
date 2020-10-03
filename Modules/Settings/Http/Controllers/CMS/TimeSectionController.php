<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\TimeSectionRequest;
use Modules\Settings\Services\CMS\TimeSectionService;

class TimeSectionController extends Controller
{
    private $timeSectionService;
    public function __construct(TimeSectionService $timeSectionService)
    {
        $this->timeSectionService = $timeSectionService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->timeSectionService->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param TimeSectionRequest $request
     * @return void
     */
    public function store(TimeSectionRequest $request)
    {
        return $this->timeSectionService->store($request);
    }

    /**
     * Show the specified resource.
     * @param TimeSectionRequest $request
     * @return void
     */
    public function show(TimeSectionRequest $request)
    {
        return $this->timeSectionService->show($request->time_section);
    }


    /**
     * Update the specified resource in storage.
     * @param TimeSectionRequest $request
     * @param int $id
     * @return void
     */
    public function update(TimeSectionRequest $request, $id)
    {
        return $this->timeSectionService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param TimeSectionRequest $request
     * @return void
     */
    public function destroy(TimeSectionRequest $request)
    {
        return $this->timeSectionService->delete($request->time_section);
    }

    public function export(PaginationRequest $request)
    {
        return $this->timeSectionService->export();
    }

}
