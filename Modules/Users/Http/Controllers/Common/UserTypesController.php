<?php

namespace Modules\Users\Http\Controllers\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Services\Common\UserTypesService;

class UserTypesController extends Controller
{
    private $userTypesService;

    public function __construct(UserTypesService $userTypesService)
    {
        $this->userTypesService = $userTypesService;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->userTypesService->index();
    }

}
