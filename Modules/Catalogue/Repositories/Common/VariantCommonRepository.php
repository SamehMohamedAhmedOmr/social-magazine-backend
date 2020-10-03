<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Variant;
use Modules\Catalogue\Entities\VariantLanguage;

class VariantCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $variant_language_model;
    protected $table_name;
    protected $pivot_table_name;

    public function __construct(Variant $variant_model, VariantLanguage $variant_language_model)
    {
        $this->model = $variant_model;
        $this->variant_language_model = $variant_language_model;
        $this->cache_key = 'variants';
    }
}
