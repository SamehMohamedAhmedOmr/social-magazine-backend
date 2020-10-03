<?php


namespace Modules\WareHouse\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\CountryRepository;
use Modules\WareHouse\Repositories\DistrictRepository;
use Modules\WareHouse\Transformers\DistrictResource;
use Modules\WareHouse\Transformers\Frontend\District\DistrictTreeResource;

class DistrictService extends LaravelServiceClass
{
    private $district_repository;
    private $countryRepository;

    public function __construct(DistrictRepository $district_repository, CountryRepository $countryRepository)
    {
        $this->district_repository = $district_repository;
        $this->countryRepository = $countryRepository;
    }

    public function index()
    {
        $sort_key =  (request('sort_key') ? request('sort_key') : 'id');
        $sort_order =  (request('sort_order') ? request('sort_order') : 'desc');
        $districts = $this->district_repository->getDistrictFront(getLang(), $sort_key, $sort_order, request('country'));
        $districts->load('child.language', 'child.child.language');

        return ApiResponse::format(200, DistrictTreeResource::collection($districts), 'district data retrieved successfully');
    }

    public function districtTree()
    {
        if (!request('country_id')) {
            $default_country_code = config('base.default_country');

            $country = $this->countryRepository->get($default_country_code, [], 'country_code');
        } else {
            $country = $this->countryRepository->get(request('country_id'), []);
        }

        $districts = $this->district_repository->all([
            'parent_id' => null,
            'country_id' => ($country) ? $country->id : null
        ]);

        $districts->load([
            'currentLanguage',
            'child.currentLanguage',
            'child.child.currentLanguage'
        ]);

        $districts = DistrictTreeResource::collection($districts);
        return ApiResponse::format(200 , $districts, 'district data retrieved successfully');
    }
}
