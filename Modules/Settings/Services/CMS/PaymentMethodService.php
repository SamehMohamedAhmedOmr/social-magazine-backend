<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\ExcelExports\PaymentMethodExport;
use Modules\Settings\Repositories\PaymentMethodRepository;
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
        if (request('is_pagination')) {
            list($payment_methods, $pagination) = parent::paginate($this->payment_method_repo, null);
        } else {
            $payment_methods = parent::list($this->payment_method_repo);

            $pagination = null;
        }

        $payment_methods->load([
            'currentLanguage'
        ]);

        $payment_methods = PaymentMethodResource::collection($payment_methods);
        return ApiResponse::format(200, $payment_methods, [], $pagination);
    }

    public function store()
    {
        $payment_method = $this->payment_method_repo->create(request()->all());

        $payment_method_languages = prepareObjectLanguages(request('data'));

        $payment_method = $this->payment_method_repo->syncLanguage($payment_method, $payment_method_languages);

        $payment_method->load([
            'languages'
        ]);

        $payment_method = PaymentMethodResource::make($payment_method);

        return ApiResponse::format(200, $payment_method, 'Payment Method added successfully');
    }

    public function show($id)
    {
        $payment_method = $this->payment_method_repo->get($id);

        $payment_method->load([
            'languages'
        ]);

        $payment_method = PaymentMethodResource::make($payment_method);

        return ApiResponse::format(200, $payment_method);
    }

    public function update($request)
    {
        $payment_method = $this->payment_method_repo->update(request('payment_method'), request()->all());

        if (request()->has('data')) {
            $payment_method_languages = prepareObjectLanguages(request('data'));

            $payment_method = $this->payment_method_repo->updateLanguage($payment_method, $payment_method_languages);
        }

        $payment_method->load([
            'languages'
        ]);

        $payment_method = PaymentMethodResource::make($payment_method);

        return ApiResponse::format(200, $payment_method, 'Payment Method updated successfully');
    }

    public function delete($id)
    {
        $payment_method = $this->payment_method_repo->delete($id);

        return ApiResponse::format(200, $payment_method);
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Payment-Methods', \App::make(PaymentMethodExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
