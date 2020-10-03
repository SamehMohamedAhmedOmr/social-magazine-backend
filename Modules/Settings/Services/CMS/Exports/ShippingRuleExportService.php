<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class ShippingRuleExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['shipping_rule_label'],
                'key' => $object['key'],
                'price' => $object['price'],
            ];
        }
        return $excel_data;
    }
}
