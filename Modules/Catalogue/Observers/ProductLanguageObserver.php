<?php

namespace Modules\Catalogue\Observers;

use Illuminate\Support\Str;
use Modules\Catalogue\Entities\ProductLanguage;
use Modules\Catalogue\Repositories\CMS\ProductRepository;

class ProductLanguageObserver
{
    private $product_repo;

    public function __construct(ProductRepository $productRepository)
    {
        $this->product_repo = $productRepository;
    }


    public function creating(ProductLanguage $product_language)
    {
        $slug = Str::slug($product_language->name, '-');
        do {
            $check_slug = $this->product_repo->getSlugIfDuplication($slug);
            $explode_number = explode('-', $check_slug);
            $number = array_key_exists(1, $explode_number) ? ((integer)$explode_number[1])+1 : 1;
            $slug = $check_slug != null ? $slug.'-'.$number : $slug;
        } while ($check_slug != null);

        $product_language->slug = $slug;
    }
}
