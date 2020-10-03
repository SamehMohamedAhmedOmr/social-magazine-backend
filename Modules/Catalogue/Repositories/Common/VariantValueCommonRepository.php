<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Variant;
use Modules\Catalogue\Entities\VariantLanguage;
use Modules\Catalogue\Entities\VariantValue;
use Modules\Catalogue\Entities\VariantValueLanguage;

class VariantValueCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $variantValueLanguage;

    public function __construct(VariantValue $variation_model, VariantValueLanguage $variantValueLanguage)
    {
        $this->model = $variation_model;
        $this->cache_key = 'variant_value';
        $this->variantValueLanguage = $variantValueLanguage;
    }
}
