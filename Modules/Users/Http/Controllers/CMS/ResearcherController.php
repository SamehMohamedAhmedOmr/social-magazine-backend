<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\ResearcherRequest;
use Modules\Users\Services\CMS\ResearcherService;
use Modules\Users\Services\Common\UserService;
use Throwable;

class ResearcherController extends Controller
{
    private $userService;
    private $researcher_service;

    public function __construct(UserService $user, ResearcherService $client)
    {
        $this->userService = $user;
        $this->researcher_service = $client;
    }


    // API RESOURCE METHODS

    /**
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->researcher_service->index();
    }

    /**
     * Handles Add New Researcher
     *
     * @param ResearcherRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ResearcherRequest $request)
    {
        return $this->researcher_service->store($request);
    }

    public function show(ResearcherRequest $request)
    {
        return $this->researcher_service->show($request->user);
    }

    public function update(ResearcherRequest $request)
    {
        return $this->researcher_service->update($request->user, $request);
    }

    public function destroy(ResearcherRequest $request)
    {
        return $this->researcher_service->delete($request->user);
    }


    /**
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function export(PaginationRequest $request)
    {
        return $this->researcher_service->export();
    }
}
