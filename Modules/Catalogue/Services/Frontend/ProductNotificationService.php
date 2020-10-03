<?php

namespace Modules\Catalogue\Services\Frontend;


use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Repositories\ProductNotificationRepository;

class ProductNotificationService extends LaravelServiceClass
{
    private $product_notification_repo;
    private $cache_key;

    public function __construct(ProductNotificationRepository $product_notification_repo)
    {
        $this->product_notification_repo = $product_notification_repo;
        $this->cache_key = 'product_notification_repo';
    }


    public function store($request = null)
    {
        $warehouses = \Session::get('warehouses_id');
        foreach ($warehouses as $warehouse){
            $this->product_notification_repo->createOrUpdate([
                'user_id' => \Auth::id(),
                'product_id' => $request->product,
                'warehouse_id' => $warehouse
            ]);
        }

        return ApiResponse::format(200, null, trans('catalogue::msg.will_notified'));
    }
}
