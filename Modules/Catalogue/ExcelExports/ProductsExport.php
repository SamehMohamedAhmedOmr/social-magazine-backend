<?php

namespace Modules\Catalogue\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Catalogue\Services\CMS\Exports\ProductsExportService;
use Modules\Catalogue\Services\CMS\ProductService;
use Modules\Base\Facade\ExcelExportHelper;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(ProductService::class),
            App::make(ProductsExportService::class), false);

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
            "SKU",
            "Brand",
            "Main Category",
            'Categories',
            "Unit of Measure",
            "Price Lists",
            'Images',
            'Variations'
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
                $cellRange = 'A1:K'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
