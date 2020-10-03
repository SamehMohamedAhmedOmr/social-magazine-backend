<?php

namespace Modules\Settings\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Services\Frontend\ConfigurationService;
use Modules\Settings\Services\Frontend\PaymentMethodService;
use Modules\Settings\Services\Frontend\TimeSectionService;

class ConfigurationController extends Controller
{
    private $configurationService;
    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->configurationService->index();
    }
}
