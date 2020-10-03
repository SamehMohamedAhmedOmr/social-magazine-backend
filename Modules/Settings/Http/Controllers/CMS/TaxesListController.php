<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\TaxesListRequest;
use Modules\Settings\Services\CMS\TaxesListService;

class TaxesListController extends Controller
{
    private $taxes_list_service;

    public function __construct(TaxesListService $taxes_list_service)
    {
        $this->taxes_list_service = $taxes_list_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->taxes_list_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param TaxesListRequest $request
     * @return JsonResponse|void
     */
    public function store(TaxesListRequest $request)
    {
        return $this->taxes_list_service->store();
    }

    /**
     * Show the specified resource.
     * @param TaxesListRequest $request
     * @return JsonResponse|void
     */
    public function show(TaxesListRequest $request)
    {
        return $this->taxes_list_service->show($request->tax_lists);
    }

    /**
     * Update the specified resource in storage.
     * @param TaxesListRequest $request
     * @return JsonResponse|void
     */
    public function update(TaxesListRequest $request)
    {
        return $this->taxes_list_service->update($request->tax_lists);
    }

    /**
     * Remove the specified resource from storage.
     * @param TaxesListRequest $request
     * @return JsonResponse|void
     */
    public function destroy(TaxesListRequest $request)
    {
        return $this->taxes_list_service->delete($request->tax_lists);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function listTaxesType()
    {
        return $this->taxes_list_service->listTaxesType();
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function listTaxesAmountType()
    {
        return $this->taxes_list_service->listTaxesAmountType();
    }

    public function export(PaginationRequest $request)
    {
        return $this->taxes_list_service->export();
    }

}
