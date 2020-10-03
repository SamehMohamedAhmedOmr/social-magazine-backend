<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class NavigationMenuExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'key' => $object['key'],
                'type' => isset($object['type']) ? $object['type']['name'] : '',
                'order' => $object['order'],
            ];
        }
        return $excel_data;
    }
}
