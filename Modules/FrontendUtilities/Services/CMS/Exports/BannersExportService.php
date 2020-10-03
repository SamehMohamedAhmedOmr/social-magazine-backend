<?php
namespace Modules\FrontendUtilities\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;

class BannersExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'image' => $object['image'],
                'link' => $object['link'],
                'order' => $object['order'],
                'enable_web' => ($object['enable_web']) ? 'YES' : 'NO',
                'enable_android' => ($object['enable_android']) ? 'YES' : 'NO',
                'enable_ios' => ($object['enable_ios']) ? 'YES' : 'NO',
            ];
        }
        return $excel_data;
    }
}
