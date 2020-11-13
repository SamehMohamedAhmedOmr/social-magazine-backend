<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Basic\Services\Common\AccountDependenciesService;

class AccountDependenciesController extends Controller
{
    private $service;

    public function __construct(AccountDependenciesService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->service->index();
    }


}
