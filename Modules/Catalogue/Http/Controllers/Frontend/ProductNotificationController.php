<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Catalogue\Http\Requests\ValidateProductIDRequest;
use Modules\Catalogue\Services\Frontend\ProductNotificationService;

class ProductNotificationController extends Controller
{
    private $product_notification_service;

    public function __construct(ProductNotificationService $product_notification_service)
    {
        $this->product_notification_service = $product_notification_service;
    }

    /**
     * Store a newly created resource in storage.
     * @param ValidateProductIDRequest $request
     * @return JsonResponse|void
     */
    public function store(ValidateProductIDRequest $request)
    {
        return $this->product_notification_service->store($request);
    }

}
