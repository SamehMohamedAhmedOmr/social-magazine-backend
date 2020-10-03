<?php

namespace Modules\Configuration\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Configuration\Facades\TenantHelper;
use Modules\Configuration\Transformers\CMS\ProjectInformationResource;

class ProjectInformationService
{
    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    public function projectInformation(){
        $sub_domain = TenantHelper::getSubDomain();

        $project_information = \Cache::get($sub_domain);

        if (!$project_information){
            $project_information = $this->configurationService->baseConfiguration($sub_domain);
        }

        if ($project_information){
            $project_information = ProjectInformationResource::make($project_information);
        }

        return ApiResponse::format(200, $project_information);
    }

}
