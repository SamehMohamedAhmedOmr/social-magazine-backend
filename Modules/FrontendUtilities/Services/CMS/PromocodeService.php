<?php


namespace Modules\FrontendUtilities\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FrontendUtilities\ExcelExports\PromocodeExport;
use Modules\FrontendUtilities\Repositories\PromocodesRepository;
use Modules\FrontendUtilities\Transformers\CMS\Promocode\PromocodeResource;

class PromocodeService extends LaravelServiceClass
{
    private $promocodes_repository;

    public function __construct(PromocodesRepository $promocodes_repository)
    {
        $this->promocodes_repository = $promocodes_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($promocodes, $pagination) = parent::paginate($this->promocodes_repository, null);
        } else {
            $promocodes = parent::list($this->promocodes_repository, true);

            $pagination = null;
        }

         $promocodes->load([
             'users',
             'products.currentLanguage',
             'categories.currentLanguage',
             'brands.currentLanguage'
         ]);

        $promocodes = PromocodeResource::collection($promocodes);
        return ApiResponse::format(200, $promocodes, [], $pagination);
    }

    public function show($id)
    {
        $promocode = $this->promocodes_repository->get($id);

        $promocode->load([
            'users',
            'products.languages',
            'categories.languages',
            'brands.languages'
        ]);

        $promocode = PromocodeResource::make($promocode);
        return ApiResponse::format(200, $promocode);
    }

    public function store()
    {
        $promocode = $this->promocodes_repository->create(request()->all());

        if (request()->has('users')) {
            $this->promocodes_repository->attachUsers($promocode, request('users'));
        }

        if (request()->has('products')) {
            $this->promocodes_repository->attachProducts($promocode, request('products'));
        }

        if (request()->has('categories')) {
            $this->promocodes_repository->attachCategories($promocode, request('categories'));
        }

        if (request()->has('brands')) {
            $this->promocodes_repository->attachBrands($promocode, request('brands'));
        }

        $promocode = PromocodeResource::make($promocode);
        return ApiResponse::format(200, $promocode);
    }

    public function update($id)
    {
        $promocode = $this->promocodes_repository->update($id, request()->all());

        if (request()->has('users')) {
            $this->promocodes_repository->updateUsers($promocode, request('users'));
        }

        if (request()->has('products')) {
            $this->promocodes_repository->updateProducts($promocode, request('products'));
        }

        if (request()->has('categories')) {
            $this->promocodes_repository->updateCategories($promocode, request('categories'));
        }

        if (request()->has('brands')) {
            $this->promocodes_repository->updateBrands($promocode, request('brands'));
        }

        $promocode = PromocodeResource::make($promocode);
        return ApiResponse::format(200, $promocode);
    }

    public function delete($id)
    {
        $promocode = $this->promocodes_repository->delete($id);
        return ApiResponse::format(200, $promocode);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Promocodes', \App::make(PromocodeExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
