<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Services\CMS\CurrencyService;

class CurrencyController extends Controller
{
    private $currency_service;

    public function __construct(CurrencyService $currency_service)
    {
        $this->currency_service = $currency_service;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->currency_service->index();
    }
}
