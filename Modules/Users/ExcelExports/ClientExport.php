<?php

namespace Modules\Users\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Users\Services\CMS\ResearcherService;
use Modules\Users\Services\CMS\Exports\ClientsExportService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(ResearcherService::class),
            App::make(ClientsExportService::class));

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
            "Email",
            "Mobile",
            'Addresses'
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
                $cellRange = 'A1:E'.$length;
                ExcelExportHelper::registerEvents($event, $cellRange);
            },
        ];
    }

}
