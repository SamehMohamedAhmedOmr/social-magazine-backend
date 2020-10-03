<?php

namespace Modules\FacebookCatalogue\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FacebookCatalogue\Services\Products\ProductService;

class ProductsController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createDynamicLinkForProducts()
    {
        return $this->productService->dynamicLinksForProducts();
    }

    public function dynamicLinksForOneProducts(Request $request)
    {
        return $this->productService->dynamicLinksForOneProducts($request->product_id);
    }

    public function sync(Request $request)
    {
        return $this->productService->sync($request);
    }
}
