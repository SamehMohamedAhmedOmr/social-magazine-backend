<?php
namespace Modules\Catalogue\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class ToppingMenuExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'toppings' => isset($object['toppings']) ? ExcelExportHelper::prepareAttachments($object['toppings'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
