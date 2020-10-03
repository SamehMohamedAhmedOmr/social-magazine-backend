<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Entities\CategoryLanguage;

class CategoryCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $category_language_model;
    protected $table_name;

    public function __construct(Category $category_model, CategoryLanguage $category_language_model)
    {
        $this->model = $category_model;
        $this->category_language_model = $category_language_model;
        $this->cache_key = 'category';
    }
}
