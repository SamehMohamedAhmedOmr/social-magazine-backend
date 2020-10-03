<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\ShippingRulesRequest;
use Modules\Settings\Services\CMS\ShippingRuleService;

class ShippingRulesController extends Controller
{
    private $shippingRuleService;

    public function __construct(ShippingRuleService $shippingRuleService)
    {
        $this->shippingRuleService = $shippingRuleService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->shippingRuleService->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param ShippingRulesRequest $request
     * @return JsonResponse|void
     */
    public function store(ShippingRulesRequest $request)
    {
        return $this->shippingRuleService->store();
    }

    /**
     * Show the specified resource.
     * @param ShippingRulesRequest $request
     * @return JsonResponse|void
     */
    public function show(ShippingRulesRequest $request)
    {
        return $this->shippingRuleService->show($request->shipping_rule);
    }

    /**
     * Update the specified resource in storage.
     * @param ShippingRulesRequest $request
     * @return JsonResponse|void
     */
    public function update(ShippingRulesRequest $request)
    {
        return $this->shippingRuleService->update($request->shipping_rule);
    }

    /**
     * Remove the specified resource from storage.
     * @param ShippingRulesRequest $request
     * @return JsonResponse|void
     */
    public function destroy(ShippingRulesRequest $request)
    {
        return $this->shippingRuleService->delete($request->shipping_rule);
    }

    public function export(PaginationRequest $request)
    {
        return $this->shippingRuleService->export();
    }
}
