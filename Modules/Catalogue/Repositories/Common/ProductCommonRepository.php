<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;
use Modules\Catalogue\Entities\ProductLanguage;
use Modules\Catalogue\Entities\ProductPrice;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;
use Modules\Settings\Entities\PriceList;
use Modules\WareHouse\Entities\ProductWarehouse;

class ProductCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $product_language_model;
    protected $product_language_model_path;
    protected $category_repo;
    protected $price_list_model;
    protected $product_price_list_model;
    protected $productWarehouseModel;

    public function __construct(
        Product $product_model,
        ProductLanguage $product_language_model,
        CategoryRepository $category_repo,
        PriceList $price_list_model,
        ProductPrice $product_price_list_model,
        ProductWarehouse $productWarehouseModel
    ) {
        $this->model = $product_model;
        $this->product_language_model = $product_language_model;
        $this->cache_key = 'product';
        $this->product_language_model_path = ProductLanguage::class;
        $this->category_repo = $category_repo;
        $this->price_list_model = $price_list_model;
        $this->product_price_list_model =$product_price_list_model;
        $this->productWarehouseModel = $productWarehouseModel;
    }
}
