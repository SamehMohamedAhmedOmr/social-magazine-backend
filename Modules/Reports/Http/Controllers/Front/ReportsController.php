<?php

namespace Modules\Reports\Http\Controllers\Front;

use Illuminate\Routing\Controller;
use Modules\Reports\Services\Front\MostPurchasedReportService;

class ReportsController extends Controller
{
    public function mostPurchased(MostPurchasedReportService $mostPurchasedReportService)
    {
        return $mostPurchasedReportService->index();
    }
}
