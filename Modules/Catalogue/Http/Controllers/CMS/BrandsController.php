<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\BrandRequest;
use Modules\Catalogue\Services\CMS\BrandService;

class BrandsController extends Controller
{
    private $brand_service;

    public function __construct(BrandService $brand_service, BrandRequest $brand_request)
    {
        $this->brand_service = $brand_service;
    }

    public function index()
    {
        return $this->brand_service->index();
    }

    public function store()
    {
        return $this->brand_service->store();
    }

    public function show()
    {
        return $this->brand_service->show(request('brand'));
    }

    public function update()
    {
        return $this->brand_service->update(request('brand'));
    }

    public function destroy()
    {
        return $this->brand_service->delete(request('brand'));
    }

    public function removeImage()
    {
        return $this->brand_service->removeImage(request('brand'));
    }

    public function addImage()
    {
        return $this->brand_service->update(request('brand'));
    }

    public function restore()
    {
        return $this->brand_service->restore(request('brand'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->brand_service->export();
    }

}
