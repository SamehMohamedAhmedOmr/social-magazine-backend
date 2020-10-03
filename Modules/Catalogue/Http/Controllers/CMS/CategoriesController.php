<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\CategoryRequest;
use Modules\Catalogue\Services\CMS\CategoryService;

class CategoriesController extends Controller
{
    private $category_service;

    public function __construct(CategoryService $category_service, CategoryRequest $category_request)
    {
        $this->category_service = $category_service;
    }

    public function index()
    {
        return $this->category_service->index();
    }

    public function store()
    {
        return $this->category_service->store();
    }

    public function show()
    {
        return $this->category_service->show(request('category'));
    }

    public function update()
    {
        return $this->category_service->update(request('category'));
    }

    public function destroy()
    {
        return $this->category_service->delete(request('category'));
    }

    public function removeImages()
    {
        return $this->category_service->removeImages(request('category'));
    }

    public function addImages()
    {
        return $this->category_service->update(request('category'));
    }

    public function restore()
    {
        return $this->category_service->restore(request('category'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->category_service->export();
    }
}
