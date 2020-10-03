<?php
namespace Modules\Catalogue\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class CategoryExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'code' => $object['code'],
                'parent_category' => isset($object['parent_category']) ? ExcelExportHelper::prepareSingleAttachment($object['parent_category'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
