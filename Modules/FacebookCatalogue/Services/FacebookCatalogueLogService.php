<?php

namespace Modules\FacebookCatalogue\Services;

use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FacebookCatalogue\Repositories\FacebookCatalogueLogRepository;

class FacebookCatalogueLogService extends LaravelServiceClass
{
    protected $facebookCatalogueLogRepository;

    public function __construct(FacebookCatalogueLogRepository $facebookCatalogueLogRepository)
    {
        $this->facebookCatalogueLogRepository = $facebookCatalogueLogRepository;
    }

    public function pagination()
    {
        $per_page = request('per_page') ?: 15;
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $data = $this->facebookCatalogueLogRepository->pagination($per_page, $order_by);
        $pagination = Pagination::preparePagination($data);
        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function delete($id)
    {
        $this->facebookCatalogueLogRepository->delete($id);
        return ApiResponse::format(204, [], 'Successful Query');
    }
}
