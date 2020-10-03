<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Catalogue\Services\Frontend\ProductService;

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

    public function show()
    {
        return $this->product_service->show(request('product'));
    }
}
