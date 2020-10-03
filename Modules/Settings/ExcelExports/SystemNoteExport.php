<?php

namespace Modules\Settings\ExcelExports;

use App;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Settings\Services\CMS\Exports\SystemNotesExportService;
use Modules\Settings\Services\CMS\SystemNoteService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SystemNoteExport implements FromArray , WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    private $data_length = 0;

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        $data = ExcelExportHelper::prepareDataForExport(App::make(SystemNoteService::class),
            App::make(SystemNotesExportService::class));

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
            "Note Key",
            "Note Body",
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
