<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\FrontendSettings\FrontendColors;


class FrontendColorsRepository extends LaravelRepositoryClass
{
    public function __construct(FrontendColors $frontend_colors)
    {
        $this->model = $frontend_colors;
        $this->cache_key = 'frontend_colors';
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

}
