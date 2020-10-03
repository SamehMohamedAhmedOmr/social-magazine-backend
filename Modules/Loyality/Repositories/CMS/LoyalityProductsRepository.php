<?php

namespace Modules\Loyality\Repositories\CMS;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\LoyalityProduct;

class LoyalityProductsRepository extends LaravelRepositoryClass
{
    public function __construct(LoyalityProduct $loyality_product)
    {
        $this->model = $loyality_product;
    }

    public function pagination($perPage = 15)
    {
        $query = $this->model->has('product')
            ->paginate($perPage);

        return [
            'data' => $query->load('product.languages'),
            'pagination' => $query
        ];
    }
}
