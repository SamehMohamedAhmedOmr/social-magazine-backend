<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class PaymentMethodsExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'key' => $object['key'],
            ];
        }
        return $excel_data;
    }
}
