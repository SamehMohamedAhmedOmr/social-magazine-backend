<?php
namespace Modules\WareHouse\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class DistrictExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'shipping_role' => isset($object['shipping_role']) ?  ExcelExportHelper::prepareSingleAttachment($object['shipping_role'], 'shipping_rule_label') : null,
                'country' => isset($object['country']) ? ExcelExportHelper::prepareSingleAttachment($object['country'], 'name') : null,
                'parent' => isset($object['parent']) ? ExcelExportHelper::prepareSingleAttachment($object['parent'], 'name') : null,
            ];
        }
        return $excel_data;
    }

}
