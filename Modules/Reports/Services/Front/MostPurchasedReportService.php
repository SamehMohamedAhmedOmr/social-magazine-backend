<?php

namespace Modules\Reports\Services\Front;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Transformers\Frontend\ProductResource;
use Modules\Reports\Repositories\MostPurchasedReportRepository;
use Pagination;

class MostPurchasedReportService extends LaravelServiceClass
{
    public function __construct(MostPurchasedReportRepository $mostPurchasedReportRepository)
    {
        $this->repository = $mostPurchasedReportRepository;
    }

    public function index()
    {
        $user_id = \Auth::id();
        $per_page = request('per_page') ?? 15;
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['total_price', 'total_quantity', 'id'])
            ? request('sort_by') : 'id';

        $query_response = $this->repository->index($user_id, $per_page, $sort_by, $order_by);
        $pagination = Pagination::preparePagination($query_response);
        $products = $this->repository->loadRelations($query_response);
        $data = [];

        foreach ($products as $item) {
            $data[] = [
                'total_quantity' => (integer)$item->total_quantity,
                'total_price' => (float)$item->total_price,
                'product' => ProductResource::make($item)
            ];
        }

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }
}
