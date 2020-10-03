<?php

namespace Modules\FrontendUtilities\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\FrontendUtilities\Services\CMS\Exports\PromocodeExportService;
use Modules\FrontendUtilities\Services\CMS\PromocodeService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PromocodeExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(PromocodeService::class),
            App::make(PromocodeExportService::class));

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
            "Code",
            "Minimum Price",
            "Maximum Price",

            "Discount Type",
            "Max Discount Amount",

            "Rewards",
            "Usage Per User",
            "Users Count",

            "From",
            "To",

            "Products",
            "Brands",
            "Categories",
            "Users",
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
                $cellRange = 'A1:P'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
