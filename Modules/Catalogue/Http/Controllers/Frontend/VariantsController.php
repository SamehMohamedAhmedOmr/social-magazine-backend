<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Catalogue\Services\Frontend\VariantService;

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

    public function show()
    {
        return $this->variant_service->show(request('variant'));
    }
}
