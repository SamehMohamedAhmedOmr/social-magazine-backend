<?php

namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\PaymentMethodRepository;
use Modules\Settings\Repositories\SystemNoteRepository;
use Modules\Settings\Repositories\SystemSettingRepository;
use Modules\Settings\Transformers\Frontend\ConfigurationResource;
use Modules\Settings\Transformers\SystemSettingResource;

class ConfigurationService extends LaravelServiceClass
{
    private $system_setting_repo;
    private $system_note_repo ;
    private $payment_method_repo;

    public function __construct(
        SystemSettingRepository $system_setting_repo,
        SystemNoteRepository $system_note_repo,
        PaymentMethodRepository $payment_method_repo
    )
    {
        $this->system_setting_repo = $system_setting_repo;
        $this->system_note_repo = $system_note_repo;
        $this->payment_method_repo = $payment_method_repo;
    }

    public function index()
    {
        $system_setting = $this->system_setting_repo->getFirstRecord();
        $system_notes = $this->system_note_repo->all([], ['language']);
        $payment_methods = $this->payment_method_repo->all();


        $configuration =  new ConfigurationResource($system_setting, $system_notes, $payment_methods);

        return ApiResponse::format(200, $configuration);
    }
}
