<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\CompanyRequest;
use Modules\Settings\Services\CMS\CompanyService;

class CompanyController extends Controller
{
    private $company_service;

    public function __construct(CompanyService $company_service)
    {
        $this->company_service = $company_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->company_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param CompanyRequest $request
     * @return JsonResponse|void
     */
    public function store(CompanyRequest $request)
    {
        return $this->company_service->store($request);
    }

    /**
     * Show the specified resource.
     * @param CompanyRequest $request
     * @return JsonResponse|void
     */
    public function show(CompanyRequest $request)
    {
        return $this->company_service->show($request->company);
    }

    /**
     * Update the specified resource in storage.
     * @param CompanyRequest $request
     * @return JsonResponse|void
     */
    public function update(CompanyRequest $request)
    {
        return $this->company_service->update($request->company, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param CompanyRequest $request
     * @return JsonResponse|void
     */
    public function destroy(CompanyRequest $request)
    {
        return $this->company_service->delete($request->company);
    }

    public function export(PaginationRequest $request)
    {
        return $this->company_service->export();
    }
}
