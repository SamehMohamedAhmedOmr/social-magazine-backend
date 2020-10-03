<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\ProductWarehouse;
use Modules\WareHouse\Entities\PurchaseOrderProduct;
use Modules\WareHouse\Entities\PurchaseReceiptProduct;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;

class PurchaseOrderProductRepository extends LaravelRepositoryClass
{
    public function __construct(PurchaseOrderProduct $purchase_order_product)
    {
        $this->model = $purchase_order_product;
        $this->cache_key = 'purchase_order_product';
    }

    public function getBulk($products, $purchase_order_id)
    {
        return $this->model->whereIn('product_id', $products)->where('purchase_order_id', $purchase_order_id)->get();
    }
}
