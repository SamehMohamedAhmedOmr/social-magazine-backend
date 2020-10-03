<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\MobileUpdateExport;
use Modules\Settings\Repositories\MobileUpdateRepository;
use Modules\Settings\Transformers\MobileUpdateConfigurationResource;
use Modules\Settings\Transformers\MobileUpdateResource;

class MobileUpdateService extends LaravelServiceClass
{
    private $mobile_update_repo;

    public function __construct(MobileUpdateRepository $mobile_update_repo)
    {
        $this->mobile_update_repo = $mobile_update_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($mobile_updates, $pagination) = parent::paginate($this->mobile_update_repo, null);
        } else {
            $mobile_updates = parent::list($this->mobile_update_repo);

            $pagination = null;
        }

        $mobile_updates = MobileUpdateResource::collection($mobile_updates);

        return ApiResponse::format(200, $mobile_updates, [], $pagination);
    }

    public function store()
    {
        $mobile_update = $this->mobile_update_repo->create(request()->all());

        $mobile_update = MobileUpdateResource::make($mobile_update);

        return ApiResponse::format(200, $mobile_update, 'Mobile Update added successfully');
    }

    public function show($id)
    {
        $mobile_update = $this->mobile_update_repo->get($id);

        $mobile_update = MobileUpdateResource::make($mobile_update);

        return ApiResponse::format(200, $mobile_update);
    }

    public function update($id)
    {
        $mobile_update = $this->mobile_update_repo->update($id, request()->all());

        $mobile_update = MobileUpdateResource::make($mobile_update);

        return ApiResponse::format(200, $mobile_update, 'Mobile Update updated successfully');
    }

    public function delete($id)
    {
        $mobile_update = $this->mobile_update_repo->delete($id);

        return ApiResponse::format(200, $mobile_update);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Mobile-Updates', \App::make(MobileUpdateExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
