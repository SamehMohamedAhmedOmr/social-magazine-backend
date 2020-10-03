<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\SystemNoteRequest;
use Modules\Settings\Services\CMS\SystemNoteService;

class SystemNotesController extends Controller
{
    private $system_note_service;

    public function __construct(SystemNoteService $system_note_service)
    {
        $this->system_note_service = $system_note_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->system_note_service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param SystemNoteRequest $request
     * @return JsonResponse|void
     */
    public function store(SystemNoteRequest $request)
    {
        return $this->system_note_service->store();
    }

    /**
     * Show the specified resource.
     * @param SystemNoteRequest $request
     * @return JsonResponse|void
     */
    public function show(SystemNoteRequest $request)
    {
        return $this->system_note_service->show($request->system_note);
    }

    /**
     * Update the specified resource in storage.
     * @param SystemNoteRequest $request
     * @return JsonResponse|void
     */
    public function update(SystemNoteRequest $request)
    {
        return $this->system_note_service->update($request->system_note);
    }

    /**
     * Remove the specified resource from storage.
     * @param SystemNoteRequest $request
     * @return JsonResponse|void
     */
    public function destroy(SystemNoteRequest $request)
    {
        return $this->system_note_service->delete($request->system_note);
    }

    public function export(PaginationRequest $request)
    {
        return $this->system_note_service->export();
    }
}
