<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\OrderIDRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\OrderNoteRequest;
use Modules\WareHouse\Services\CMS\Order\OrderNotesService;

class OrderNotesController extends Controller
{
    private $order_notes_service;
    public function __construct(OrderNotesService $order_notes_service)
    {
        $this->order_notes_service = $order_notes_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @param OrderIDRequest $orderIDRequest
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, OrderIDRequest $orderIDRequest)
    {
        return $this->order_notes_service->index($orderIDRequest);
    }

    /**
     * Display a one resource.
     * @param OrderNoteRequest $request
     * @param $id
     * @return void
     */
    public function show(OrderNoteRequest $request, $id)
    {
        return $this->order_notes_service->show($id);
    }

    /**
     * Store a newly created resource in storage.
     * @param OrderNoteRequest $request
     * @return JsonResponse
     */
    public function store(OrderNoteRequest $request)
    {
        return $this->order_notes_service->store($request);
    }

    /**
     * Update the specified resource in storage.
     * @param OrderNoteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OrderNoteRequest $request, $id)
    {
        return $this->order_notes_service->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param OrderNoteRequest $request
     * @return JsonResponse
     */
    public function destroy(OrderNoteRequest $request)
    {
        return $this->order_notes_service->delete($request->orders_note);
    }

    public function export(PaginationRequest $request, OrderIDRequest $orderIDRequest)
    {
        return $this->order_notes_service->export($orderIDRequest);
    }
}
