<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\PageExport;
use Modules\Settings\Repositories\FrontendSettings\PagesRepository;
use Modules\Settings\Transformers\PageResource;

class PagesService extends LaravelServiceClass
{
    private $pages_repository;

    public function __construct(PagesRepository $pages_repository)
    {
        $this->pages_repository = $pages_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($pages, $pagination) = parent::paginate($this->pages_repository, null, true);
        } else {
            $pages = parent::list($this->pages_repository, true);

            $pagination = null;
        }

        $pages->load('currentLanguage');
        $pages = PageResource::collection($pages);

        return ApiResponse::format(200, $pages, [], $pagination);
    }

    public function store($request = null)
    {
        $pages = $this->pages_repository->create($request->all());

        // store language
        $languages = prepareObjectLanguages($request->languages);

        $pages = $this->pages_repository->syncLanguage($pages, $languages);

        $pages->load('languages');

        $pages = PageResource::make($pages);

        return ApiResponse::format(200, $pages, 'Page added successfully');
    }

    public function show($id)
    {
        $pages = $this->pages_repository->get($id);

        $pages->load('languages');

        $pages = PageResource::make($pages);

        return ApiResponse::format(200, $pages);
    }

    public function update($id, $request = null)
    {
        $pages = $this->pages_repository->update($id, $request->all());

        if (isset($request->languages)) {
            $languages = prepareObjectLanguages($request->languages);

            $pages = $this->pages_repository->updateLanguage($pages, $languages);
        }

        $pages->load('languages');

        $pages = PageResource::make($pages);

        return ApiResponse::format(200, $pages, 'Page updated successfully');
    }

    public function delete($id)
    {
        $pages = $this->pages_repository->delete($id);

        return ApiResponse::format(200, $pages);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Pages', \App::make(PageExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
