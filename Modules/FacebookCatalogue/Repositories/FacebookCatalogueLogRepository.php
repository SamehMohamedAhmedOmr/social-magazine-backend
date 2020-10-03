<?php

namespace Modules\FacebookCatalogue\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FacebookCatalogue\Entities\FacebookCatalogueLog;

class FacebookCatalogueLogRepository extends LaravelRepositoryClass
{
    public function __construct(FacebookCatalogueLog $facebookCatalogueLog)
    {
        $this->model = $facebookCatalogueLog;
    }

    public function create(array $data, $with = [])
    {
        return $this->model->create($data);
    }

    public function pagination($per_page = 15, $order_by = 'id')
    {
        return $this->model->orderBy('id', $order_by)->paginate($per_page);
    }
}
