<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Order;

use App\Http\Controllers\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\StoreOrderStatusRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\UpdateOrderStatusRequest;
use Modules\WareHouse\Services\CMS\Order\OrderStatusService;

class OrderStatusController extends Controller
{
    protected $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    public function index()
    {
        return $this->orderStatusService->index();
    }

    public function store(StoreOrderStatusRequest $request)
    {
        return $this->orderStatusService->store();
    }

    public function show()
    {
        return $this->orderStatusService->show(request('status'));
    }

    public function update(UpdateOrderStatusRequest $request)
    {
        return $this->orderStatusService->update(request('status'));
    }

    public function destroy()
    {
        return $this->orderStatusService->delete(request('status'));
    }

    public function restore()
    {
        return $this->orderStatusService->restore(request('status'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->orderStatusService->export();
    }
}
