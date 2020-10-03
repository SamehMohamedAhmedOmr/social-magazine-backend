<?php

namespace Modules\WareHouse\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CartRequest;
use Modules\WareHouse\Http\Requests\Frontend\CartCalculationRequest;
use Modules\WareHouse\Services\Frontend\CartCalculationService;
use Modules\WareHouse\Services\Frontend\CartService;

class CartController extends Controller
{
    private $cart_service;
    private $calculationService;

    public function __construct(
        CartService $cart_service,
        CartCalculationService $calculationService
    )
    {
        $this->cart_service = $cart_service;
        $this->calculationService = $calculationService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->cart_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param CartRequest $request
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function store(CartRequest $request)
    {
        return $this->cart_service->store();
    }

    /**
     * Store a newly created resource in storage.
     * @param CartCalculationRequest $request
     * @return JsonResponse|void
     */
    public function cartCalculation(CartCalculationRequest $request)
    {
        return $this->calculationService->cartCalculation($request);
    }
}
