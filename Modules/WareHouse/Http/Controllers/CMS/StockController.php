<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CMS\Stock\AddStockRequest;
use Modules\WareHouse\Http\Requests\CMS\Stock\ImportNotifications;
use Modules\WareHouse\Http\Requests\CMS\Stock\MoveStockRequest;
use Modules\WareHouse\Http\Requests\CMS\Stock\ProductIdRequest;
use Modules\WareHouse\Http\Requests\CMS\Stock\ProductWarehouseRequest;
use Modules\WareHouse\Http\Requests\CMS\Stock\SellWithAvailabilityRequest;
use Modules\WareHouse\Http\Requests\StockRequest;
use Modules\WareHouse\Http\Requests\StockSheetRequest;
use Modules\WareHouse\Services\CMS\ManageStockService;
use Modules\WareHouse\Services\CMS\StockImportService;

class StockController extends Controller
{
    protected $stock_service;
    protected $manage_stock_service;

    public function __construct(StockImportService $stock_service, ManageStockService $manage_stock_service)
    {
        $this->stock_service = $stock_service;
        $this->manage_stock_service = $manage_stock_service;
    }

    /**
     * upload Stock Sheet.
     * @param StockSheetRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadStockSheet(StockSheetRequest $request)
    {
        return $this->stock_service->uploadStockSheet();
    }

    /**
     * Store a newly created resource in storage.
     * @param StockRequest $request
     * @return void
     * @throws Exception
     */
    public function store(StockRequest $request)
    {
        return $this->stock_service->store();
    }

    /**
     * list available product quantity in all warehouses
     * @param PaginationRequest $paginationRequest
     * @param ProductIdRequest $request
     * @return JsonResponse
     */
    public function allProductQuantity(PaginationRequest $paginationRequest, ProductIdRequest $request)
    {
        return $this->manage_stock_service->allProductQuantity($request);
    }


    /**
     * list available product quantity in main warehouse and summations in other warehouses and the same for variations
     * @param ProductIdRequest $request
     * @return JsonResponse
     */
    public function availableProductQuantity(ProductIdRequest $request)
    {
        return $this->manage_stock_service->availableProductQuantity($request);
    }


    /**
     * Get all stock logs in product
     * @param PaginationRequest $paginationRequest
     * @param ProductIdRequest $request
     * @return JsonResponse
     */
    public function productStockLogs(PaginationRequest $paginationRequest, ProductIdRequest $request)
    {
        return $this->manage_stock_service->productStockLogs($request);
    }

    /**
     * Add New Quantity
     * @param AddStockRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function addQuantity(AddStockRequest $request)
    {
        return $this->manage_stock_service->addQuantity($request);
    }

    /**
     * Move Quantity
     * @param MoveStockRequest $request
     * @return JsonResponse
     */
    public function moveQuantity(MoveStockRequest $request)
    {
        return $this->manage_stock_service->moveQuantity($request);
    }

    public function productWarehouseQuantity(ProductWarehouseRequest $request)
    {
        return $this->manage_stock_service->productWarehouseQuantity($request);
    }

    public function sellWithAvailability(SellWithAvailabilityRequest $request)
    {
        return $this->manage_stock_service->sellWithAvailability($request);
    }


    public function sendNotification(ImportNotifications $request)
    {
        return $this->stock_service->ProductNotifications($request);
    }
}
