<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Facade\Pagination;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;
use Modules\WareHouse\Entities\ProductWarehouse;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;

class ProductWarehouseRepository extends LaravelRepositoryClass
{
    protected $productModel;

    public function __construct(ProductWarehouse $product_warehouse,
                                Product $productModel)
    {
        $this->model = $product_warehouse;
        $this->productModel = $productModel;
        $this->cache_key = 'product_warehouse';
    }

    public function getBulk($products, $warehouse)
    {
        return $this->model->whereIn('product_id', $products)->where('warehouse_id', $warehouse)->get();
    }

    public function setBulk($products, $warehouseId, $available)
    {
        foreach ($products as $product) {
            $this->model->updateOrInsert([
                'product_id' => $product,
                'warehouse_id' => $warehouseId
            ],['available' => (boolean)$available]);

            if ((boolean)$available) {
                $this->productModel->where('id', $product)
                    ->update([
                        'is_sell_with_availability' => true,
                        'max_quantity_per_order' => 5
                    ]);
            }
        }

        return true;
    }

    public function getProduct($warehouse, $product_id)
    {
        // eager loading
        return $warehouse->products()->where('product_id', $product_id)->first();
    }

    public function updateQuantity($warehouse, $products_id, $product_warehouse)
    {
        $warehouse->products()->detach($products_id);

        $warehouse->products()->attach($product_warehouse);
    }

    /* CMS Methods */
    public function paginate($per_page = 15, $conditions = null, $search_keys = null,
                             $sort_key = 'warehouse_id', $sort_order = 'asc', $lang = null, $warehouse_ids = [])
    {
        $query = $this->model;

        $query = $query->whereIn('warehouse_id', $warehouse_ids);

        $model_data = parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);

        $pagination = Pagination::preparePagination($model_data);

        return [$model_data, $pagination];

    }
}
