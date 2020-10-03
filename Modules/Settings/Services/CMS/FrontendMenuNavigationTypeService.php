<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\FrontendSettings\FrontendMenuNavigationTypeRepository;
use Modules\Settings\Transformers\CMS\FrontendSettings\FrontendMenuNavigationTypeResource;

class FrontendMenuNavigationTypeService extends LaravelServiceClass
{
    private $frontendMenuNavigationTypeRepository;

    public function __construct(FrontendMenuNavigationTypeRepository $frontendMenuNavigationTypeRepository)
    {
        $this->frontendMenuNavigationTypeRepository = $frontendMenuNavigationTypeRepository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($menu_navigation_type, $pagination) = parent::paginate($this->frontendMenuNavigationTypeRepository,
                null, false);
        } else {
            $menu_navigation_type = parent::list($this->frontendMenuNavigationTypeRepository, false);
            $pagination = null;
        }

        $menu_navigation_type = FrontendMenuNavigationTypeResource::collection($menu_navigation_type);

        return ApiResponse::format(200, $menu_navigation_type, [], $pagination);
    }


}
