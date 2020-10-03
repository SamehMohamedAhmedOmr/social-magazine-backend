<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Catalogue\Services\Frontend\CategoryService;

class CategoriesController extends Controller
{
    private $category_service;

    public function __construct(CategoryService $category_service)
    {
        $this->category_service = $category_service;
    }

    public function index()
    {
        return $this->category_service->index();
    }

    public function show()
    {
        return $this->category_service->show(request('category'));
    }
}
