<?php

namespace Modules\FacebookCatalogue\Services\Products;

use Illuminate\Support\Facades\Storage;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\FacebookCatalogue\Exports\Products\ExportProduct;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FacebookCatalogue\Services\DynamicLinkService;
use Modules\FacebookCatalogue\Transformers\CMS\Products\ProductResource;
use Modules\FacebookCatalogue\Repositories\FacebookCatalogueLogRepository;

class ProductService extends LaravelServiceClass
{
    protected $exportProduct;

    protected $productRepository;

    protected $dynamicLinkService;

    protected $facebookCatalogueLogRepository;

    public function __construct(
        ExportProduct $exportProduct,
        ProductRepository $productRepository,
        FacebookCatalogueLogRepository $facebookCatalogueLogRepository,
        DynamicLinkService $dynamicLinkService
    )
    {
        $this->productRepository = $productRepository;
        $this->exportProduct = $exportProduct;
        $this->dynamicLinkService = $dynamicLinkService;
        $this->facebookCatalogueLogRepository = $facebookCatalogueLogRepository;
    }

    public function dynamicLinksForProducts()
    {
        $data = $this->productRepository->dynamicLinksForProducts($this->dynamicLinkService);
        return ApiResponse::format(200, [], 'Updated Successfully');
    }

    public function dynamicLinksForOneProducts($product_id)
    {
        $data = $this->productRepository->dynamicLinksForOneProduct($product_id, $this->dynamicLinkService);
        $data = ProductResource::collection($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function sync($request)
    {
        $export_type = $request->export_type && in_array($request->export_type, ['ads', 'facebook_shop',
                'instagram_shop', 'marketplace']) ? $request->export_type : 'ads';
        $file_name = 'products_feed';
        Excel::store($this->exportProduct, 'facebook/catalogue/'.$file_name.'.csv', 'public');
        $file_url = '/facebook/catalogue/'.$file_name.'.csv';
        $data = $this->facebookCatalogueLogRepository->create([
            'file_name' => $file_name.'.csv',
            'file_url' => url(Storage::url($file_url)),
            'export_type' => $export_type
        ]);
        return Excel::download($this->exportProduct, $file_name.'.csv');
    }
}
