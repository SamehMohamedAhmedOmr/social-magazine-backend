<?php

namespace Modules\FrontendUtilities\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\FrontendUtilities\Services\CMS\BannerService;
use Modules\FrontendUtilities\Services\CMS\Exports\BannersExportService;
use Modules\Base\Facade\ExcelExportHelper;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BannersExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(BannerService::class),
            App::make(BannersExportService::class));

        $this->data_length = count($data);
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            "ID",
            "Image",
            "Link",
            "Order",
            "Enable Web",
            "Enable Android",
            "Enable IOS",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return ExcelExportHelper::styles();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event)
            {
                $length = $this->data_length + 1;
                $cellRange = 'A1:H'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
