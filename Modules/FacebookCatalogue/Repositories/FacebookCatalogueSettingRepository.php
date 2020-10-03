<?php

namespace Modules\FacebookCatalogue\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FacebookCatalogue\Entities\FacebookCatalogueSettings;

class FacebookCatalogueSettingRepository extends LaravelRepositoryClass
{
    public function __construct(FacebookCatalogueSettings $facebookCatalogueSettings)
    {
        $this->model = $facebookCatalogueSettings;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        return $this->model->first();
    }

    public function updateOrCreate(array $attributes)
    {
        return $this->model->updateOrCreate([], $attributes);
    }
}
