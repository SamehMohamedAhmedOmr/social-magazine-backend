<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;


class PriceListExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['price_list_name'],
                'key' => $object['key'],
                'currency' => isset($object['currency']) ? ExcelExportHelper::prepareSingleAttachment($object['currency'], 'name') : null,
                'type' => isset($object['type']) ? ExcelExportHelper::prepareSingleAttachment($object['type'], 'name') : null,
                'country' => isset($object['country']) ? ExcelExportHelper::prepareSingleAttachment($object['country'], 'name') : null,
            ];
        }
        return $excel_data;
    }

}
