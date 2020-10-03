<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\VariantValueRequest;
use Modules\Catalogue\Services\CMS\VariantValueService;

class VariantValuesController extends Controller
{
    private $variation_service;

    public function __construct(VariantValueService $variation_service)
    {
        $this->variation_service = $variation_service;
    }

    public function index()
    {
        return $this->variation_service->index();
    }

    public function store(VariantValueRequest $variation_request)
    {
        return $this->variation_service->store();
    }

    public function show()
    {
        return $this->variation_service->show(request('variant_value'));
    }

    public function update(VariantValueRequest $variation_request)
    {
        return $this->variation_service->update(request('variant_value'));
    }

    public function destroy()
    {
        return $this->variation_service->delete(request('variant_value'));
    }

    public function restore()
    {
        return $this->variation_service->restore(request('variant_value'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->variation_service->export();
    }
}
