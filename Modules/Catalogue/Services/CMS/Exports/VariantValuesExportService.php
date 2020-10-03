<?php
namespace Modules\Catalogue\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class VariantValuesExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'code' => $object['code'],
                'value' => $object['value'],
                'palette_image' => $object['palette_image'],
                'variant' => isset($object['variant']) ? ExcelExportHelper::prepareSingleAttachment($object['variant'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
