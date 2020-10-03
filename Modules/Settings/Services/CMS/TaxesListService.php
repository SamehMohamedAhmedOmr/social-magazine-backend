<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\TaxesListExport;
use Modules\Settings\Repositories\TaxesListAmountTypeRepository;
use Modules\Settings\Repositories\TaxesListRepository;
use Modules\Settings\Repositories\TaxesListTypesRepository;
use Modules\Settings\Transformers\TaxesListResource;
use Modules\Settings\Transformers\TaxesTypeResource;

class TaxesListService extends LaravelServiceClass
{
    private $tax_list_repo;
    private $taxes_List_Types_repo;
    private $taxes_List_Amount_Type_repo;

    public function __construct(
        TaxesListRepository $tax_list_repo,
        TaxesListTypesRepository $taxes_List_Types_repo,
        TaxesListAmountTypeRepository $taxes_List_Amount_Type_repo
    )
    {
        $this->tax_list_repo = $tax_list_repo;
        $this->taxes_List_Types_repo = $taxes_List_Types_repo;
        $this->taxes_List_Amount_Type_repo = $taxes_List_Amount_Type_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($tax_lists, $pagination) = parent::paginate($this->tax_list_repo, getLang());
        } else {
            $tax_lists = parent::list($this->tax_list_repo);

            $pagination = null;
        }

         $tax_lists->load([
             'taxType', 'amountType', 'currentLanguage'
         ]);

        $tax_lists = TaxesListResource::collection($tax_lists);
        return ApiResponse::format(200, $tax_lists, [], $pagination);
    }

    public function store()
    {
        $tax_list = $this->tax_list_repo->create(request()->all());

        $taxes_list_languages = prepareObjectLanguages(request('data'));

        $tax_list = $this->tax_list_repo->syncLanguage($tax_list, $taxes_list_languages);

        $tax_list = TaxesListResource::make($tax_list);

        return ApiResponse::format(200, $tax_list, 'Tax added successfully');
    }

    public function show($id)
    {
        $tax_list = $this->tax_list_repo->get($id);

        $tax_list->load([
            'language',
            'country.currentLanguage',
            'taxType',
            'amountType'
        ]);

        $tax_list = TaxesListResource::make($tax_list);

        return ApiResponse::format(200, $tax_list);
    }

    public function update($id)
    {
        $tax_list = $this->tax_list_repo->update($id, request()->all());

        if (request()->has('data')) {
            $taxes_list_languages = prepareObjectLanguages(request('data'));

            $tax_list = $this->tax_list_repo->updateLanguage($tax_list, $taxes_list_languages);
        }

        $tax_list = TaxesListResource::make($tax_list);

        return ApiResponse::format(200, $tax_list, 'Tax updated successfully');
    }

    public function delete($id)
    {
        $tax_list = $this->tax_list_repo->delete($id);

        return ApiResponse::format(200, $tax_list);
    }

    public function listTaxesType()
    {
        $tax_lists_types = $this->taxes_List_Types_repo->all();

        $tax_lists_types = TaxesTypeResource::collection($tax_lists_types);

        return ApiResponse::format(200, $tax_lists_types);
    }

    public function listTaxesAmountType()
    {
        $tax_lists_amount_types = $this->taxes_List_Amount_Type_repo->all();

        $tax_lists_amount_types = TaxesTypeResource::collection($tax_lists_amount_types);

        return ApiResponse::format(200, $tax_lists_amount_types);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Taxes-lists', \App::make(TaxesListExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
