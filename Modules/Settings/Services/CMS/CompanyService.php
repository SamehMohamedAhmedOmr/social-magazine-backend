<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\CompanyExport;
use Modules\Settings\Repositories\CompanyRepository;
use Modules\Settings\Transformers\CompanyResource;

class CompanyService extends LaravelServiceClass
{
    private $company_repo;

    public function __construct(CompanyRepository $company_repo)
    {
        $this->company_repo = $company_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($companies, $pagination) = parent::paginate($this->company_repo, getLang(), false);
        } else {
            $companies = parent::list($this->company_repo, false);

            $pagination = null;
        }

        $companies->load([
            'currentLanguage',
            'companyImg.galleryType'
        ]);
        $companies = CompanyResource::collection($companies);

        return ApiResponse::format(200, $companies, [], $pagination);
    }

    public function store($request = null)
    {
        $company = $this->company_repo->create($request->all());

        // store language
        $company_languages = prepareObjectLanguages($request->data);

        $company = $this->company_repo->syncLanguage($company, $company_languages);

        $company->load([
            'companyImg.galleryType'
        ]);

        $company = CompanyResource::make($company);

        return ApiResponse::format(200, $company, 'Company added successfully');
    }

    public function show($id)
    {
        $company = $this->company_repo->get($id);

        $company->load([
            'language',
            'companyImg.galleryType'
        ]);

        $company = CompanyResource::make($company);

        return ApiResponse::format(200, $company);
    }

    public function update($id, $request = null)
    {
        $company = $this->company_repo->update($id,$request->all());

        if (isset($request->data)) {
            $company_languages = prepareObjectLanguages($request->data);

            $company = $this->company_repo->updateLanguage($company, $company_languages);
        }

        $company->load([
            'companyImg.galleryType'
        ]);

        $company = CompanyResource::make($company);

        return ApiResponse::format(200, $company, 'Company updated successfully');
    }

    public function delete($id)
    {
        $company = $this->company_repo->delete($id);

        return ApiResponse::format(200, $company);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Companies', \App::make(CompanyExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
