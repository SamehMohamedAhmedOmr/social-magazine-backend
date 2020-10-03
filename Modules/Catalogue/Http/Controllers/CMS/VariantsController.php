<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\VariantRequest;
use Modules\Catalogue\Services\CMS\VariantService;

class VariantsController extends Controller
{
    private $variant_service;

    public function __construct(VariantService $variant_service)
    {
        $this->variant_service = $variant_service;
    }

    public function index()
    {
        return $this->variant_service->index();
    }

    public function store(VariantRequest $variant_request)
    {
        return $this->variant_service->store();
    }

    public function show()
    {
        return $this->variant_service->show(request('variant'));
    }

    public function update(VariantRequest $variant_request)
    {
        return $this->variant_service->update(request('variant'));
    }

    public function destroy()
    {
        return $this->variant_service->delete(request('variant'));
    }

    public function restore()
    {
        return $this->variant_service->restore(request('variant'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->variant_service->export();
    }
}
