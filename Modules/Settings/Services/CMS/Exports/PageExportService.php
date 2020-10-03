<?php
namespace Modules\Settings\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class PageExportService extends ExportServiceClass
{
    public function prepareData($objects){
        $excel_data = [];
        foreach ($objects as $object){

            $excel_data [] = [
                'id' => $object['id'],
                'page_url' => $object['page_url'],
                'title' => $object['title'],
                'content' => $object['content'],
                'seo_title' => $object['seo_title'],
                'seo_description' => $object['seo_description'],
            ];
        }
        return $excel_data;
    }
}
