<?php
namespace Modules\WareHouse\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;


class WarehouseExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['warehouse_id'],
                'name' => $object['name'],
                'warehouse_code' => $object['warehouse_code'],
                'districts' => isset($object['districts']) ?  ExcelExportHelper::prepareAttachments($object['districts'], 'name') : null,
            ];
        }
        return $excel_data;
    }

    private function prepareSingleAttachment($object){
        $object = collect($object)->toArray();
        return isset($object['name']) ? $object['name'] : null;
    }

    private function prepareAttachments($objects){
        $all = [];
        $objects = collect($objects)->toArray();
        foreach ($objects as $object){
            $name = isset($object['name']) ? $object['name'] : null;

            if (isset($name)){
                $all [] = $name;
            }
        }
        return collect($all)->implode("\n");
    }



}
