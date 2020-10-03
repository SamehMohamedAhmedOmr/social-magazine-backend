<?php

namespace Modules\Catalogue\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Catalogue\Services\CMS\BrandService;
use Modules\Catalogue\Services\CMS\Exports\BrandExportService;
use Modules\Catalogue\Services\CMS\Exports\ProductsExportService;
use Modules\Catalogue\Services\CMS\ProductService;
use Modules\Base\Facade\ExcelExportHelper;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BrandExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(BrandService::class),
            App::make(BrandExportService::class), false);

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
            "Name",
            "Icon",
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
                $cellRange = 'A1:D'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
