<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\FrontendSettings\FrontendTypography;

class FrontendTypographyRepository extends LaravelRepositoryClass
{
    public function __construct(FrontendTypography $frontend_typography)
    {
        $this->model = $frontend_typography;
        $this->cache_key = 'frontend_typography';
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

}
