<?php
namespace Modules\FrontendUtilities\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class PromocodeExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'code' => $object['code'],
                'minimum_price' => $object['minimum_price'],
                'maximum_price' => $object['maximum_price'],

                'discount_type' => ($object['discount_type']) ? 'FIXED' : 'PERCENTAGE',
                'max_discount_amount' => $object['max_discount_amount'],
                'reward' => $object['reward'],
                'usage_per_user' => $object['usage_per_user'],
                'users_count' => $object['users_count'],

                'from' => $object['from'],
                'to' => $object['to'],

                'products' =>  isset($object['products']) ? ExcelExportHelper::prepareAttachments($object['products'], 'name') : null,
                'brands' =>  isset($object['brands']) ? ExcelExportHelper::prepareAttachments($object['brands'], 'name') : null,
                'categories' =>  isset($object['categories']) ? ExcelExportHelper::prepareAttachments($object['categories'], 'name') : null,
                'users' => isset($object['users']) ? ExcelExportHelper::prepareAttachments($object['users'], 'name') : null,
            ];
        }
        return $excel_data;
    }
}
