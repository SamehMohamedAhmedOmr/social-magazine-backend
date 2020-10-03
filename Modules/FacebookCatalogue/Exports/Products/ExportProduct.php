<?php

namespace Modules\FacebookCatalogue\Exports\Products;

use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Catalogue\Repositories\Frontend\ProductRepository;
use Modules\FacebookCatalogue\Transformers\CMS\Products\ProductResource;

class ExportProduct implements FromCollection, WithHeadings
{
    use Exportable;

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function collection()
    {
        $data = $this->productRepository->pagination(
            100000,
            [],
            [],
            'id',
            'desc',
            Session::get('language_id')
        );
        $data = $this->productRepository->changeToEloquent($data->items());

        return ProductResource::collection($data);
    }

    public function headings() : array
    {
        return [
            'id',
            'title',
            'description',
            'condition',
            'brand',
            'category',
            'google_product_category',
            'price',
            'link',
            'image_link',
            'availability',
            'inventory',
            'shipping_weight',
            ];
    }
}
