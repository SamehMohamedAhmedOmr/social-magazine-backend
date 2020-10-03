<?php

namespace Modules\WareHouse\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\WarehouseExport;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Repositories\WarehouseRepository;
use Modules\WareHouse\Transformers\WarehouseResource;

class WarehouseService extends LaravelServiceClass
{
    private $warehouse_repo, $district_repo;

    public function __construct(WarehouseRepository $warehouse_repo, DistrictRepository $district_repo)
    {
        $this->warehouse_repo = $warehouse_repo;
        $this->district_repo = $district_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($warehouses, $pagination) = parent::paginate($this->warehouse_repo, getLang());
        } else {
            $warehouses = parent::list($this->warehouse_repo);

            $pagination = null;
        }

        $warehouses->load([
            'currentLanguage',
            'district.currentLanguage'
        ]);

        $warehouses = WarehouseResource::collection($warehouses);
        return ApiResponse::format(200, $warehouses, [], $pagination);
    }

    public function store()
    {
        // store warehouse main data
        $warehouse = $this->warehouse_repo->create(request()->all());

        // putting default value in status didn't sent
        $warehouse->is_active = request('is_active', 1);

        // store language
        $warehouse_house_languages = prepareObjectLanguages(request('data'));

        $warehouse->Language()->sync($warehouse_house_languages);

        if (request('all')){
            $districts = $this->district_repo->all([
                'is_active' => 1
            ]);
            $districts = $districts->pluck('id')->toArray();
            $warehouse->district()->sync($districts);
        }
        else{
            $warehouse->district()->sync(request('districts'));
        }

        $warehouse->load([
            'language',
            'district.currentLanguage',
            'country.currentLanguage',
            'currentLanguage'
        ]);

        $warehouse = WarehouseResource::make($warehouse);
        return ApiResponse::format(200, $warehouse, 'Warehouse Added Successfully');
    }

    public function show($id)
    {
        $warehouse = $this->warehouse_repo->get($id, [], 'id', [
            'language','district.currentLanguage','country.currentLanguage', 'currentLanguage'
        ]);

        $warehouse = WarehouseResource::make($warehouse);
        return ApiResponse::format(200, $warehouse);
    }

    public function update($id)
    {
        // store warehouse main data
        $warehouse = $this->warehouse_repo->update($id, request()->all());

        // store language
        if (request()->has('data')) {
            $warehouse_house_languages = prepareObjectLanguages(request('data'));

            $warehouse->Language()->detach();
            $warehouse->Language()->attach($warehouse_house_languages);
        }

        if (request('all')){
            $districts = $this->district_repo->all([
                'is_active' => 1
            ]);
            $districts = $districts->pluck('id')->toArray();
            $warehouse->district()->detach();
            $warehouse->district()->attach($districts);
        }
        else if (request()->has('districts')) {
            $warehouse->district()->detach();
            $warehouse->district()->attach(request('districts'));
        }


        $warehouse->load([
            'language',
            'district.language',
            'country.language',
            'currentLanguage'
        ]);

        $warehouse = WarehouseResource::make($warehouse);
        return ApiResponse::format(200, $warehouse, 'Warehouse updated Successfully');
    }

    public function delete($id)
    {
        $warehouse = $this->warehouse_repo->delete($id);
        return ApiResponse::format(200, $warehouse, 'Warehouse Deleted Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Warehouses', \App::make(WarehouseExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
