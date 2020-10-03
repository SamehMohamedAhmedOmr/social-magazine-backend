<?php
namespace Modules\Users\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class AdminsExportService extends ExportServiceClass
{
    public function prepareData($clients){
        $excel_data = [];
        foreach ($clients as $client){

            $excel_data [] = [
                'Admin id' => $client['id'],
                'Admin Name' => $client['name'],
                'Admin Email' => $client['email'],
                'countries' => isset($client['countries']) ? $this->prepareAttachments($client['countries']) : null,
                'warehouses' => isset($client['warehouses']) ? $this->prepareAttachments($client['warehouses']) : null,
                'roles' => isset($client['roles']) ? $this->prepareAttachments($client['roles']) : null,
            ];
        }
        return $excel_data;
    }

    private function prepareAttachments($objects){
        $all = [];
        $objects = collect($objects)->toArray();
        foreach ($objects as $object){
            $name = isset($object['name']) ? $object['name'] : null;

            if (isset($name)){
                $all [] = $name;
            }
        }
        return collect($all)->implode("\n");
    }



}
