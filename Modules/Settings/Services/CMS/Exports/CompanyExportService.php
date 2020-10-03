<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class CompanyExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'logo' => $object['logo'],
            ];
        }
        return $excel_data;
    }
}
