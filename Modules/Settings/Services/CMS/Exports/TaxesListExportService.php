<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;


class TaxesListExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'key' => $object['key'],
                'price' => $object['price'],
                'amount_type' => isset($object['amount_type']) ? ExcelExportHelper::prepareSingleAttachment($object['amount_type'], 'name') : null,
                'tax_type' => isset($object['tax_type']) ? ExcelExportHelper::prepareSingleAttachment($object['tax_type'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
