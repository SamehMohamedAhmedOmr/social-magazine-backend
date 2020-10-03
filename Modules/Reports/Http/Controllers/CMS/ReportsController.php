<?php

namespace Modules\Reports\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Reports\Services\CMS\CMSDashboardReportService;
use Modules\Reports\Services\CMS\DashboardReportService;
use Modules\Reports\Services\CMS\DistrictReportService;
use Modules\Reports\Services\CMS\EndOfDayReportService;
use Modules\Reports\Services\CMS\FinancialReportService;
use Modules\Reports\Services\CMS\SalesReportService;
use Modules\Reports\Services\CMS\StockReportService;

class ReportsController extends Controller
{
    public function cmsDashboard(CMSDashboardReportService $CMSDashboardReportService)
    {
        return $CMSDashboardReportService->index();
    }

    public function dashboard(DashboardReportService $dashboardReportService)
    {
        return $dashboardReportService->index();
    }

    public function districts(DistrictReportService $districtReportService)
    {
        return $districtReportService->index();
    }

    public function stock(StockReportService $stockReportService)
    {
        return $stockReportService->index();
    }

    public function stockImport(StockReportService $stockReportService)
    {
        return $stockReportService->import();
    }

    public function stockImportDelete(StockReportService $stockReportService)
    {
        return $stockReportService->importDelete();
    }

    public function sales(SalesReportService $salesReportService)
    {
        return $salesReportService->index();
    }

    public function endOfDay(EndOfDayReportService $endOfDayReportService)
    {
        return $endOfDayReportService->index();
    }

    public function financial(FinancialReportService $financialReportService)
    {
        return $financialReportService->index();
    }
}
