<?php
namespace Modules\WareHouse\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class OrderNoteExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'note' => $object['note'],
            ];
        }
        return $excel_data;
    }

}
