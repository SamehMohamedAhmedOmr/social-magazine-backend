<?php

namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\FrontendSettings\PagesRepository;
use Modules\Settings\Transformers\PageResource;

class PagesService extends LaravelServiceClass
{
    private $pages_repository;

    public function __construct(PagesRepository $pages_repository)
    {
        $this->pages_repository = $pages_repository;
    }

    public function show($key)
    {
        $conditions = [
            'is_active' => 1
        ];

        if (is_numeric($key)){
            $pages = $this->pages_repository->get($key, $conditions);
        }
        else{
            $pages = $this->pages_repository->get($key,$conditions,'page_url');
        }

        $pages->load('currentLanguage');

        $pages = PageResource::make($pages);

        return ApiResponse::format(200, $pages);
    }

}
