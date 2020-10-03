<?php

namespace Modules\Reports\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Transformers\Frontend\Facades\VariantByProductResource;
use Modules\Reports\Repositories\StockReportRepository;
use Pagination;
use ProductHelper;

class StockReportService extends LaravelServiceClass
{
    const IMPORT_TYPES = [0 => 'Added', 1 => 'Moved'];
    protected $repository;

    public function __construct(StockReportRepository $stockReportRepository)
    {
        $this->repository = $stockReportRepository;
    }

    public function index()
    {
        $per_page = request('per_page') ?? 15;
        $stock_qty = request('stock_qty') && in_array(request('stock_qty'), ['>', '='])
            ? request('stock_qty') : null;
        $category_id = request('category_id') !== null ? request('category_id') : null;
        $min_price = request()->has('min_price') && request('min_price') != null // product_price_list.product_id
            ? (float) request('min_price') : null;
        $max_price = request()->has('max_price') && request('max_price') != null // product_price_list.product_id
            ? (float) request('max_price') : null;
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'id'])
            ? request('sort_by') : 'id';

        $warehouse = request('warehouses') !== null && is_array(request('warehouses')) ? request('warehouses') : Session::get('warehouses_id');

        $products = $this->repository->index(
            $warehouse,
            $stock_qty,
            $category_id,
            $min_price,
            $max_price,
            $per_page,
            $sort_by,
            $order_by
        );

        $pagination = Pagination::preparePagination($products);

        $products = $products->load('variantValues');
        $data = [];

        foreach ($products as $product) {
            $pro = $product->toArray();
            $pro['variants'] =  VariantByProductResource::toArray($product->variantValues) ?? [];
            unset($pro['variant_values']);
            $data[] = $pro;
        }

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function import()
    {
        $per_page = request('per_page') ?? 15;
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'id', 'date'])
            ? request('sort_by') : 'id';
        $from_warehouse = is_array(request('from_warehouse')) ? request('from_warehouse') : null;
        $to_warehouse = is_array(request('to_warehouse')) ? request('to_warehouse') : null;
        $date = request('date');

        $query = $this->repository->import($per_page, $from_warehouse, $to_warehouse, $date, $sort_by, $order_by);

        $pagination = Pagination::preparePagination($query);

        $query = $query->load('user', 'fromWarehouse', 'toWarehouse');

        $data = [];
        foreach ($query as $item) {
            $data[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'user_id' => $item->user ? $item->user->name : null,
                'user_name' => $item->user->id,
                'from_warehouse_id' => $item->from_warehouse,
                'to_warehouse_id' => $item->to_warehouse,
                'from_warehouse_name' => $item->from_warehouse ? $item->fromWarehouse->currentLanguage->name : null,
                'to_warehouse_name' => $item->to_warehouse ? $item->toWarehouse->currentLanguage->name : null,
                'date' => $item->created_at->format('Y-m-d H:i'),
                'file_path' => url('storage/'. $item->file_name),
                'type' => self::IMPORT_TYPES[$item->type]
            ];
        }
        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function importDelete()
    {
        $this->repository->deleteLog(request()->log_id);
        return ApiResponse::format(204, [], 'Successful Query');
    }
}
