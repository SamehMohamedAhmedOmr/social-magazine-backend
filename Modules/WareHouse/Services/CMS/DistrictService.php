<?php


namespace Modules\WareHouse\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\DistrictExport;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Transformers\DistrictParentResource;
use Modules\WareHouse\Transformers\DistrictResource;

class DistrictService extends LaravelServiceClass
{
    private $district_repository;

    public function __construct(DistrictRepository $district_repository)
    {
        $this->district_repository = $district_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($districts, $pagination) = parent::paginate($this->district_repository, getLang());

        } else {
            $districts = parent::list($this->district_repository);

            $pagination = null;
        }

        $districts->load('currentLanguage', 'country.currentLanguage', 'shippingRule', 'parentDistrict.currentLanguage');

        return ApiResponse::format(200, DistrictResource::collection($districts), null, $pagination);
    }

    // store new country accept only request
    public function store($request = null)
    {
        $request_data = $request->all();
        /* Save main country Object */
        $district = $this->district_repository->create($request_data,
            ['language','country.language', 'shippingRule', 'parentDistrict'])
        ;
        /* save multi language for banner object*/
        $this->district_repository->asyncObjectLanguages($request->data, $district);
        return ApiResponse::format(200, DistrictResource::make($district), 'district created successfully');
    }

    public function show($id)
    {
        $district = $this->district_repository->get($id, [], 'id', ['language','country.language', 'shippingRule', 'parentDistrict']);
        return ApiResponse::format(200, DistrictResource::make($district), 'district data retrieved successfully');
    }

    // update country , take id and request
    public function update($id, $request = null)
    {
        $requestData = $request->all();
        $requestData['parent_id'] = ($request->has('parent_id')) ?  $requestData['parent_id'] : null;
        /* update main banner Object */
        $district = $this->district_repository->update($id, $requestData, [], 'id');

        $district->load([
            'language',
            'country.language',
            'shippingRule',
            'parentDistrict'
        ]);

        if (isset($request->data)) {
            $this->district_repository->asyncObjectLanguages($request->data, $district);
        }
        return ApiResponse::format(200, DistrictResource::make($district), 'district updated successfully');
    }

    // delete country accept country id
    public function delete($id)
    {
        $this->district_repository->delete($id);
        return ApiResponse::format(200, [], 'district deleted successfully');
    }

    public function listDistrict() // parent / not
    {
         $is_active = request('is_active') ? 1 : 0;
        if (request('parent') == 1) {
            $conditions = [
                ['parent_id', null],
                ['country_id', request('country_id')],
                ['is_active', $is_active]
            ];
        } else {
            $conditions = [
                ['parent_id', '<>', null],
                ['is_active' ,$is_active]
            ];
        }

        $districts = $this->district_repository->all($conditions);

        $districts->load([
            'currentLanguage'
        ]);

        return ApiResponse::format(200, DistrictParentResource::collection($districts), 'district data retrieved successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Districts', \App::make(DistrictExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
