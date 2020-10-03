<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\ToppingMenuRequest;
use Modules\Catalogue\Services\CMS\ToppingMenuService;

class ToppingMenusController extends Controller
{
    private $topping_menu_service;

    public function __construct(ToppingMenuService $topping_menu_service)
    {
        $this->topping_menu_service = $topping_menu_service;
    }

    public function index()
    {
        return $this->topping_menu_service->index();
    }

    public function store(ToppingMenuRequest $topping_menu_request)
    {
        return $this->topping_menu_service->store();
    }

    public function show()
    {
        return $this->topping_menu_service->show(request('topping_menu'));
    }

    public function update(ToppingMenuRequest $topping_menu_request)
    {
        return $this->topping_menu_service->update(request('topping_menu'));
    }

    public function destroy()
    {
        return $this->topping_menu_service->delete(request('topping_menu'));
    }

    public function restore()
    {
        return $this->topping_menu_service->restore(request('topping_menu'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->topping_menu_service->export();
    }
}
