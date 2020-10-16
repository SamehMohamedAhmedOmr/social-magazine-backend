<?php

namespace Modules\Users\Http\Controllers\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\AccountRequest;
use Modules\Users\Services\Common\AccountService;
use Throwable;

class UsersController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }


    // API RESOURCE METHODS

    /**
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->accountService->index();
    }

    /**
     * Handles Add New Researcher
     *
     * @param AccountRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(AccountRequest $request)
    {
        return $this->accountService->store($request);
    }

    public function show(AccountRequest $request)
    {
        return $this->accountService->show($request->user);
    }

    public function update(AccountRequest $request)
    {
        return $this->accountService->update($request->user, $request);
    }

    public function destroy(AccountRequest $request)
    {
        return $this->accountService->delete($request->user);
    }

}
