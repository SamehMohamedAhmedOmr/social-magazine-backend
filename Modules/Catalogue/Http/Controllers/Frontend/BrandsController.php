<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Catalogue\Services\Frontend\BrandService;

class BrandsController extends Controller
{
    private $brand_service;

    public function __construct(BrandService $brand_service)
    {
        $this->brand_service = $brand_service;
    }

    public function index()
    {
        return $this->brand_service->index();
    }

    public function show()
    {
        return $this->brand_service->show(request('brand'));
    }
}
