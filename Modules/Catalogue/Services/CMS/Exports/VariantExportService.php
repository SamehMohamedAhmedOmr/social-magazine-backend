<?php
namespace Modules\Catalogue\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class VariantExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'values' => isset($object['values']) ? ExcelExportHelper::prepareAttachments($object['values'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
