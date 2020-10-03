<?php

namespace Modules\Loyality\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Loyality\Http\Requests\CMS\LoyaliyProgramRequest;
use Modules\Loyality\Services\CMS\LoyalityProductService;
use Modules\Loyality\Services\CMS\LoyalityProgramService;

class LoyalityProductsController extends Controller
{
    private $loyality_product_service;

    public function __construct(LoyalityProductService $loyality_product_service)
    {
        $this->loyality_product_service = $loyality_product_service;
    }

    public function index()
    {
        return $this->loyality_product_service->pagination();
    }

    public function store()
    {
        return $this->loyality_product_service->store();
    }

    public function show()
    {
        return $this->loyality_product_service->show(request()->product);
    }

    public function update()
    {
        return $this->loyality_product_service->update(request()->product);
    }

    public function destroy()
    {
        return $this->loyality_product_service->delete(request()->product);
    }
}
