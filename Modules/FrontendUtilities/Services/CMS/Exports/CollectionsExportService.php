<?php
namespace Modules\FrontendUtilities\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class CollectionsExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'order' => $object['order'],
                'enable_web' => ($object['enable_web']) ? 'YES' : 'NO',
                'enable_android' => ($object['enable_android']) ? 'YES' : 'NO',
                'enable_ios' => ($object['enable_ios']) ? 'YES' : 'NO',
                'products' => isset($object['products']) ? ExcelExportHelper::prepareAttachments($object['products'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
