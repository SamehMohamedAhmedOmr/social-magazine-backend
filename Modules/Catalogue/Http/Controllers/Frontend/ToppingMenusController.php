<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Catalogue\Services\Frontend\ToppingMenuService;

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

    public function show()
    {
        return $this->topping_menu_service->show(request('topping_menu'));
    }
}
