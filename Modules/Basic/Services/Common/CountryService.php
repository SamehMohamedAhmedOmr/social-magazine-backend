<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Repositories\CountryRepository;
use Modules\Basic\Transformers\CountryResource;

class CountryService extends LaravelServiceClass
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index()
    {
        $country = $this->countryRepository->all();
        $country = CountryResource::collection($country);
        return ApiResponse::format(200, $country);
    }

}
