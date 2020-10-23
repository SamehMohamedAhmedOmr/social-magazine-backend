<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\PublicationRuleRequest;
use Modules\Sections\Services\CMS\PublicationRuleService;
use Throwable;

class PublicationRulesController extends Controller
{
    private $service;

    public function __construct(PublicationRuleService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param PublicationRuleRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PublicationRuleRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param PublicationRuleRequest $request
     * @return JsonResponse|void
     */
    public function show(PublicationRuleRequest $request)
    {
        return $this->service->show($request->publication_rule);
    }

    /**
     * Update the specified resource in storage.
     * @param PublicationRuleRequest $request
     * @return JsonResponse|void
     */
    public function update(PublicationRuleRequest $request)
    {
        return $this->service->update($request->publication_rule, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param PublicationRuleRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PublicationRuleRequest $request)
    {
        return $this->service->delete($request->publication_rule);
    }

}
