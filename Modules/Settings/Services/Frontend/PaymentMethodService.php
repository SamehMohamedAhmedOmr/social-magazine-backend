<?php


namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\PaymentMethodRepository;
use Modules\Settings\Repositories\TimeSectionRepository;
use Modules\Settings\Transformers\DaysFrontResource;
use Modules\Settings\Transformers\PaymentMethodResource;

class PaymentMethodService extends LaravelServiceClass
{
    private $payment_method_repo;

    public function __construct(PaymentMethodRepository $payment_method_repo)
    {
        $this->payment_method_repo = $payment_method_repo;
    }

    public function index()
    {
        list($payment_methods, $pagination) = parent::paginate($this->payment_method_repo, null, true, [
            'is_active' => 1
        ]);

        $payment_methods = PaymentMethodResource::collection($payment_methods);
        return ApiResponse::format(200, $payment_methods, [], $pagination);
    }
}
