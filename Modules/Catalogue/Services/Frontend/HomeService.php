<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\BrandRepository;
use Modules\Catalogue\Repositories\Frontend\CategoryRepository;
use Modules\Catalogue\Transformers\Frontend\BrandResource;
use Modules\Catalogue\Transformers\Frontend\CategoryResource;
use Modules\FrontendUtilities\Repositories\BannerRepository;
use Modules\FrontendUtilities\Repositories\CollectionRepository;
use Modules\FrontendUtilities\Transformers\Frontend\BannerResource;
use Modules\FrontendUtilities\Transformers\Frontend\CollectionsResource;

class HomeService extends LaravelServiceClass
{
    private $category_repo;
    private $banner_repo;
    private $brand_repo;
    private $collection_repo;


    public function __construct(
        CategoryRepository $category_repo,
        BannerRepository $banner_repo,
        BrandRepository $brand_repo,
        CollectionRepository $collection_repo
    ) {
        $this->category_repo = $category_repo;
        $this->banner_repo = $banner_repo;
        $this->brand_repo = $brand_repo;
        $this->collection_repo = $collection_repo;
    }

    public function index()
    {
        $banners = $this->banners();
//        $ads = $this->ads();
       $brands = $this->brands();
       $categories = $this->categories();
       $collections = $this->collections();


        return ApiResponse::format(200, [
            'slider' => BannerResource::collection($banners),
           'categories' => CategoryResource::collection($categories),
            'collection' => CollectionsResource::collection($collections),
            'ads' => [],
           'brands' => BrandResource::collection($brands),
        ], 'Successfully');
    }

    private function brands()
    {
        $filter_language = Session::get('language_id');

        $per_page = request()->per_page ?: 15;

        $data = $this->brand_repo->paginate(
            $per_page,
            [],
            null,
            'name',
            'desc',
            $filter_language
        );

        $data->load([
            'brandImg.galleryType'
        ]);

        return $data->items();
    }

    private function categories()
    {
        $filter_language = Session::get('language_id');

        $data = $this->category_repo->paginate(15, [
            'categories.parent_id' => null
        ], null, 'name', 'desc', $filter_language);

        return $this->category_repo->loadRelations($data,[
            'categoryIcon.galleryType',
            'categoryImg.galleryType',
        ]);
    }

    // prepare Collections
    private function collections()
    {
        list($collections) = parent::paginate(
            $this->collection_repo,
            Session::get('language_id'),
            false,
            ['is_active' => 1]
        );

        $collections->load([
            'products' => function ($query) {
                $query->where(function ($qu) {
                    $qu->where('brand_id', null)->orWhereHas('brand', function ($q) {
                        $q->where('is_active', true);
                    })->whereHas('mainCategory', function ($q) {
                        $q->where('is_active', true);
                    });
                })->has('price')->where('is_active', true)->where('parent_id', null);
            },
            'products.priceLists',
            'products.price',
            'products.currentLanguage',
            'products.warehouses',
            'products.brand.currentLanguage',
            'products.mainCategory.currentLanguage',
            'products.unitOfMeasure.currentLanguage',
            'products.variantValues.currentLanguage',
            'products.variantValues' => function ($query) {
                $query->where('is_active', true);
            },
            'products.variantValues.variant' => function ($query) {
                $query->where('is_active', true);
            },
            'products.variantValues.variant.currentLanguage',
        ]);

        return $collections;
    }

    private function banners()
    {
        list($banners) = parent::paginate(
            $this->banner_repo,
            Session::get('language_id'),
            false,
            [
                'enable_ios' => true,
                'enable_android' => true,
                'is_active' => true,
            ]
        );

        $banners->load([
            'currentLanguage',
            'bannerImg.galleryType'
        ]);

        return $banners;
    }

    // prepare Ads
    private function ads()
    {
        // TODO
        return [];
    }
}
