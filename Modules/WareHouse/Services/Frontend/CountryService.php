<?php


namespace Modules\WareHouse\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\WareHouse\Repositories\CountryRepository;
use Modules\WareHouse\Transformers\Frontend\country\CountryResource;

class CountryService
{
    private $country_repository;

    public function __construct(CountryRepository $country_repository)
    {
        $this->country_repository = $country_repository;
    }

    public function index()
    {
        $sort_key =  (request('sort_key') ? request('sort_key') : 'id');
        $sort_order =  (request('sort_order') ? request('sort_order') : 'desc');
        $countries = $this->country_repository->getCountriesFront(getLang(), $sort_key, $sort_order);
        $countries->load([
            'district.language',
            'district.child.language',
            'district.child.child.language'
        ]);

        return ApiResponse::format(200, CountryResource::collection($countries), 'Country data retrieved successfully');
    }
}
