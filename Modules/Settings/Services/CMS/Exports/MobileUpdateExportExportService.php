<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class MobileUpdateExportExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'device_type' => $object['device_type'],
                'application_version' => $object['application_version'],
                'build_number' => $object['build_number'],
                'force_update' => ($object['force_update']) ? 'YES' : 'NO',
                'release_date' => $object['release_date'],
            ];
        }
        return $excel_data;
    }
}
