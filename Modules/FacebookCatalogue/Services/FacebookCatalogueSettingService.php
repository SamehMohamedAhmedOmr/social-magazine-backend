<?php

namespace Modules\FacebookCatalogue\Services;

use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FacebookCatalogue\Repositories\FacebookCatalogueSettingRepository;

class FacebookCatalogueSettingService extends LaravelServiceClass
{
    protected $facebookCatalogueSettingRepository;

    public function __construct(FacebookCatalogueSettingRepository $facebookCatalogueSettingRepository)
    {
        $this->facebookCatalogueSettingRepository = $facebookCatalogueSettingRepository;
    }
    public function updateOrCreate($request)
    {
        $data = $this->facebookCatalogueSettingRepository->updateOrCreate($request->all());
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function show($id = null)
    {
        $data = $this->facebookCatalogueSettingRepository->get(null) ?? [];
        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
