<?php
namespace Modules\WareHouse\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class CountryExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'country_code' => $object['country_code'],
                'image' => $object['image'],
            ];
        }
        return $excel_data;
    }

}
