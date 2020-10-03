<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\ClientRequest;
use Modules\Users\Http\Requests\CMS\ClientProductFilterRequest;
use Modules\Users\Services\CMS\ClientService;
use Modules\Users\Services\Common\UserService;
use Throwable;

class ClientController extends Controller
{
    private $userService;
    private $client_service;

    public function __construct(UserService $user, ClientService $client)
    {
        $this->userService = $user;
        $this->client_service = $client;
    }


    // API RESOURCE METHODS

    /**
     *
     * @param PaginationRequest $request
     * @param ClientProductFilterRequest $clientProductFilterRequest
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, ClientProductFilterRequest $clientProductFilterRequest)
    {
        return $this->client_service->index();
    }

    /**
     * Handles Add New Client
     *
     * @param ClientRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ClientRequest $request)
    {
        return $this->client_service->store($request);
    }

    public function show(ClientRequest $request)
    {
        return $this->client_service->show($request->user);
    }

    public function update(ClientRequest $request)
    {
        return $this->client_service->update($request->user, $request);
    }

    public function destroy(ClientRequest $request)
    {
        return $this->client_service->delete($request->user);
    }

    public function clientOrders(ClientRequest $request)
    {
        return $this->client_service->clientOrders($request->user);
    }

    /**
     *
     * @param PaginationRequest $request
     * @param ClientProductFilterRequest $clientProductFilterRequest
     * @return JsonResponse
     */
    public function export(PaginationRequest $request, ClientProductFilterRequest $clientProductFilterRequest)
    {
        return $this->client_service->export();
    }
}
