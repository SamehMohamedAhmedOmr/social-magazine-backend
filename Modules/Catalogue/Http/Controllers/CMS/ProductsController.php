<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\ProductRequest;
use Modules\Catalogue\Http\Requests\UploadImageRequest;
use Modules\Catalogue\Services\CMS\ProductService;

class ProductsController extends Controller
{
    private $product_service;

    public function __construct(ProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    public function index()
    {
        return $this->product_service->index();
    }

    public function store(ProductRequest $request)
    {
        return $this->product_service->store();
    }

    public function show()
    {
        return $this->product_service->show(request('product'));
    }

    public function update(ProductRequest $request)
    {
        return $this->product_service->update(request('product'));
    }

    public function destroy()
    {
        return $this->product_service->delete(request('product'));
    }

    public function restore()
    {
        return $this->product_service->restore(request('product'));
    }

    public function storeImages(UploadImageRequest $request)
    {
        return $this->product_service->storeImages($request->product_id, $request->images);
    }

    public function export(PaginationRequest $request)
    {
        return $this->product_service->export();
    }
}
