<?php
namespace Modules\Catalogue\Services\CMS\Exports;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Services\Classes\ExportServiceClass;

class ProductsExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'name' => $object['name'],
                'SKU' => $object['sku'],
                'Brand' => isset($object['brand']) ? ExcelExportHelper::prepareSingleAttachment($object['brand'], 'name') : null,
                'main_category' => isset($object['main_category']) ? ExcelExportHelper::prepareSingleAttachment($object['main_category'], 'name') : null,
                'categories' => isset($object['categories']) ? ExcelExportHelper::prepareAttachments($object['categories'], 'name') : null,
                'UOM' => isset($object['unit_of_measure']) ? ExcelExportHelper::prepareSingleAttachment($object['unit_of_measure'], 'name') : null,
                'price_lists' => isset($object['price_lists']) ? $this->preparePriceLists($object['price_lists']) : null,
                'images' => isset($object['images']) ? ExcelExportHelper::prepareAttachments($object['images'], 'image') : null,
                'variations' => isset($object['variations']) ? ExcelExportHelper::prepareAttachments($object['variations'], 'name') : null,
            ];
        }
        return $excel_data;
    }

    public function preparePriceLists($objects){
        $all = [];
        $objects = collect($objects)->toArray();
        foreach ($objects as $object){
            $name = isset($object['price_list']) ? ExcelExportHelper::prepareSingleAttachment($object['price_list'], 'price_list_name') : null;

            if (isset($name)){
                $all [] = $name . ' - ' . $object['price'];
            }
        }
        return collect($all)->implode("\n");
    }
}
