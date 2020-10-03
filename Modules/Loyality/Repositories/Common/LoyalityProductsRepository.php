<?php

namespace Modules\Loyality\Repositories\Common;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\LoyalityProduct;

class LoyalityProductsRepository extends LaravelRepositoryClass
{
    public function __construct(LoyalityProduct $loyality_product)
    {
        $this->model = $loyality_product;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        return $this->model->where($column, $value)->first();
    }

    public function getWeights($products_ids)
    {
        return $this->model->whereIn('product_id', $products_ids)
            ->where('is_active', true)->pluck('weight', 'product_id')->toArray();
    }
}
