<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\FrontendSettings\FrontendSocialMedia;

class FrontendSocialMediaRepository extends LaravelRepositoryClass
{
    public function __construct(FrontendSocialMedia $frontend_social_media)
    {
        $this->model = $frontend_social_media;
        $this->cache_key = 'frontend_social_media';
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

}
