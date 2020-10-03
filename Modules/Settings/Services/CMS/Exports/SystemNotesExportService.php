<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class SystemNotesExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'note_key' => $object['note_key'],
                'note_body' => $object['note_body'],
            ];
        }
        return $excel_data;
    }
}
