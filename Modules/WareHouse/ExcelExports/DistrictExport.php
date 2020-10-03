<?php

namespace Modules\WareHouse\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\WareHouse\Services\CMS\CountryService;
use Modules\WareHouse\Services\CMS\DistrictService;
use Modules\WareHouse\Services\CMS\Exports\CountryExportService;
use Modules\WareHouse\Services\CMS\Exports\DistrictExportService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DistrictExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(DistrictService::class),
            App::make(DistrictExportService::class));

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
            "Shipping Rule",
            'Country',
            'Parent District'
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
                $cellRange = 'A1:F'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
