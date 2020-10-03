<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\ProductWarehouse;
use Modules\WareHouse\Entities\PurchaseOrderProduct;
use Modules\WareHouse\Entities\PurchaseReceiptProduct;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;

class PurchaseReceiptProductRepository extends LaravelRepositoryClass
{
    public function __construct(PurchaseReceiptProduct $purchase_receipt_product)
    {
        $this->model = $purchase_receipt_product;
        $this->cache_key = 'purchase_receipt_product';
    }

    public function getBulk($purchase_receipts)
    {
        return $this->model->whereIn('purchase_receipt_id', $purchase_receipts)->get();
    }
}
