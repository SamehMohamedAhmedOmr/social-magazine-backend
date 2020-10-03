<?php

namespace Modules\Settings\Services\CMS;

use Modules\Settings\ExcelExports\PriceListExport;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\PriceListRepository;
use Modules\Settings\Transformers\PriceListResource;

class PriceListService extends LaravelServiceClass
{
    private $price_list_repo;

    public function __construct(PriceListRepository $price_list_repo)
    {
        $this->price_list_repo = $price_list_repo;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($price_lists, $pagination) = parent::paginate($this->price_list_repo, null);
        } else {
            $price_lists = parent::list($this->price_list_repo);

            $pagination = null;
        }

        $price_lists->load('currency','PriceListType','country.currentLanguage');

        $price_lists = PriceListResource::collection($price_lists);

        return ApiResponse::format(200, $price_lists, [], $pagination);
    }

    public function store()
    {
        $price_list = $this->price_list_repo->create([
            'country_id' => request('country_id'),
            'currency_code' => request('currency_code'),
            'price_list_name' => request('price_list_name'),
            'type' => request('type'),
            'key' => request('key'),
            'is_special' => request('is_special'),
        ], ['currency','PriceListType','country.language']);

        $price_list = PriceListResource::make($price_list);

        return ApiResponse::format(200, $price_list, 'Price list added successfully');
    }

    public function show($id)
    {
        $price_list = $this->price_list_repo->get($id, [], 'id', ['currency','PriceListType','country.currentLanguage']);

        $price_list = PriceListResource::make($price_list);

        return ApiResponse::format(200, $price_list);
    }

    public function update($request)
    {
        $validated_data = collect($request->validated());

        $validated_data = $validated_data->except(['price_list']);

        $price_list = $this->price_list_repo->update(
            request('price_list'),
            $validated_data->toArray(),
            [],
            'id',
            ['currency','PriceListType','country.language']
        );

        $price_list = PriceListResource::make($price_list);

        return ApiResponse::format(200, $price_list, 'Price list updated successfully');
    }

    public function delete($id)
    {
        $price_list = $this->price_list_repo->delete($id);

        return ApiResponse::format(200, $price_list);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Price-lists', \App::make(PriceListExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
