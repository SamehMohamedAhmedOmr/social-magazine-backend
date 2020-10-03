<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\ProductRepository;
use Modules\Catalogue\Transformers\Frontend\Facades\VariantByProductResource;

class SearchSettingService extends LaravelServiceClass
{
    private $product_repo;

    public function __construct(ProductRepository $product_repo)
    {
        $this->product_repo = $product_repo;
    }

    public function settings()
    {
        $language = Session::get('language_id');
        $data = $this->product_repo->searchSettings();
//        $data['variants'] = VariantByProductResource::toArray(implode(',', $data['product_ids']), $language);
        unset($data['product_ids']);
        return ApiResponse::format(200, $data, 'Successful');
    }
}
