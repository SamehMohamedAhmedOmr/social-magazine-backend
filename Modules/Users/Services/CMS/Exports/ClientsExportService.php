<?php
namespace Modules\Users\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;


class ClientsExportService extends ExportServiceClass
{
    public function prepareData($clients){
        $excel_data = [];
        foreach ($clients as $client){

            $excel_data [] = [
                'Client id' => $client['id'],
                'Customer Name' => $client['name'],
                'Customer Email' => $client['email'],
                'Phone' => isset($client['phone']) ? $client['phone'] : '',
                'Address' => isset($client['addresses']) ? $this->prepareAllAddresses($client['addresses']) : null,
            ];
        }
        return $excel_data;
    }

    private function prepareAllAddresses($addresses){
        $all_addresses = [];
        foreach ($addresses as $address){
            $area = isset($address) ? $this->prepareArea($address) : null;

            $all_addresses [] = isset($address) ? $this->prepareAddress($address, $area) : null;
        }
        return collect($all_addresses)->implode("\n");
    }

    private function prepareArea($address){
        $area = null;
        if (isset($address['district'])){
            $district = collect($address['district']);

            $area = isset($district['name']) ? $district['name'] : null;
        }
        return $area;
    }

    private function prepareAddress($address, $district_name){

        $full_address = $district_name;

        $title = $address['title'];
        $street = $address['street'];
        $building_no = $address['building_no'];
        $apartment_no = $address['apartment_no'];
        $floor_no = $address['floor_no'];

        $full_address = $this->attachAddress($full_address, $street, ($full_address) ? ',Street:' : 'Street:');

        if ($title){
            $full_address = $this->attachAddress($full_address, $title, ',');
        }

        if ($building_no | $apartment_no | $floor_no){
            $full_address .= '/';
        }

        $full_address = $this->attachAddress($full_address, $building_no, 'Building Number:');
        $full_address = $this->attachAddress($full_address, $apartment_no, ($building_no) ? ',Apartment Number:' : 'Apartment Number:');
        $full_address = $this->attachAddress($full_address, $floor_no, ($apartment_no || $building_no) ? ',Floor Number:' : 'Floor Number:');


        return $full_address;
    }

    private function attachAddress($full_address, $value , $title){
        if ($value){
            $full_address = $full_address . $title . strval($value) ;
        }
        return $full_address;
    }

}
