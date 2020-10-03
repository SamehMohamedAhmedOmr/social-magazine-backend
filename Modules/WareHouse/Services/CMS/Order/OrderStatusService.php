<?php

namespace Modules\WareHouse\Services\CMS\Order;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\WareHouse\ExcelExports\OrderStatusExport;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\WareHouse\Transformers\CMS\Order\OrderStatusResource;
use Modules\WareHouse\Transformers\Repo\Order\OrderStatusResource as CmsOrderStatusResource;

class OrderStatusService extends LaravelServiceClass
{
    protected $orderStatusRepository;
    protected $languageRepo;
    protected $cache_key;

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        LanguageRepository $languageRepo
    )
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->languageRepo = $languageRepo;
        $this->cache_key = 'order-statuses';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->orderStatusRepository->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $data = OrderStatusResource::collection($this->orderStatusRepository->loadRelations($data,[
            'currentLanguage'
        ]));
        return ApiResponse::format(200, $data);
    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->orderStatusRepository->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = OrderStatusResource::collection($this->orderStatusRepository->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key');
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'created_at'])
            ? request('sort_by') : 'id';
        $sort_language = \Session::get('language_id');

        $search['trashed'] = request()->has('trashed')
            ? filter_var(request('trashed'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;

        $per_page = request()->per_page ?: 15;

        return [
            'search' => $search,
            'order_by' => $order_by,
            'sort_by' => $sort_by,
            'per_page' => $per_page,
            'sort_language' => $sort_language,
        ];
    }

    public function show($orderStatusId)
    {
        $data = $this->orderStatusRepository->get($orderStatusId);
        $data = OrderStatusResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = CmsOrderStatusResource::toArray($this->languageRepo);
        $data = $this->orderStatusRepository->create($data);
        $data = OrderStatusResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($orderStatusId)
    {
        $this->orderStatusRepository->delete($orderStatusId);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($orderStatusId)
    {
        $data = CmsOrderStatusResource::toArray($this->languageRepo);
        $data = $this->orderStatusRepository->update($orderStatusId, $data);
        $data = OrderStatusResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($orderStatusId)
    {
        $data = $this->orderStatusRepository->restore($orderStatusId);
        $data = OrderStatusResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Order-Statues', \App::make(OrderStatusExport::class));

        return ApiResponse::format(200, $file_path);
    }


}
