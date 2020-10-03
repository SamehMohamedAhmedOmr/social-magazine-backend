<?php

namespace Modules\Catalogue\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Http\Requests\CMS\UnitOfMeasureRequest;
use Modules\Catalogue\Services\CMS\UnitOfMeasureService;

class UnitsOfMeasureController extends Controller
{
    private $unit_of_measure_service;

    public function __construct(UnitOfMeasureService $unit_of_measure_service, UnitOfMeasureRequest $unit_of_measure_request)
    {
        $this->unit_of_measure_service = $unit_of_measure_service;
    }

    public function index()
    {
        return $this->unit_of_measure_service->index();
    }

    public function show()
    {
        return $this->unit_of_measure_service->show(request('units_of_measure'));
    }

    public function store()
    {
        return $this->unit_of_measure_service->store();
    }

    public function update()
    {
        return $this->unit_of_measure_service->update(request('units_of_measure'));
    }

    public function destroy()
    {
        return $this->unit_of_measure_service->delete(request('units_of_measure'));
    }

    public function restore()
    {
        return $this->unit_of_measure_service->restore(request('units_of_measure'));
    }

    public function export(PaginationRequest $request)
    {
        return $this->unit_of_measure_service->export();
    }

}
