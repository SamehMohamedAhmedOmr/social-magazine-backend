<?php
namespace Modules\ACL\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;


class RolesExportService extends ExportServiceClass
{
    public function prepareData($clients){
        $excel_data = [];
        foreach ($clients as $client){

            $excel_data [] = [
                'Role id' => $client['id'],
                'Role Name' => $client['name'],
                'Role key' => $client['key'],
                'permission' => isset($client['permission']) ? ExcelExportHelper::prepareAttachments($client['permission'], 'name') : null,
            ];
        }
        return $excel_data;
    }

}
