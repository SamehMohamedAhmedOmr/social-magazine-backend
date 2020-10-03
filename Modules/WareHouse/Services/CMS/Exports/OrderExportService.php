<?php
namespace Modules\WareHouse\Services\CMS\Exports;

use Modules\Base\Services\Classes\ExportServiceClass;
use Modules\WareHouse\Repositories\CMS\Order\OrderRepository;


class OrderExportService extends ExportServiceClass
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function getBulkForExport()
    {
        $user = \Auth::user();

        $warehouses_id = [];

        $admin = $user->admin;
        if ($admin) {
            $warehouses =$user->admin->warehouses;
            if (count($warehouses)) {
                $warehouses_id = $warehouses->pluck('id')->toArray();
            }
        }

        return $this->orderRepository->getBulk(
            request('orders'),
            $warehouses_id
        );
    }

    public function prepareData($orders){
        $excel_data = [];
        foreach ($orders as $order){
            $user = collect($order['user']);
            $address = isset($order['address']) ? collect($order['address']) : null;
            $products = collect($order['cart']);

            $area = isset($address) ? $this->prepareArea($address) : null;

            $full_address = isset($address) ? $this->prepareAddress($address, $area) : null;

            $products = $this->prepareProducts($products);

            $excel_data [] = [
                'Order Date' => $order['created_at'],
                'Order Id' => $order['order_id'],
                'Customer Name' => $user['name'],
                'Phone' => isset($user['phone']) ? $user['phone'] : '',

                'Area' => $area,
                'Address' => $full_address,
                'Items' => $products,

                'Payment Method' => $order['payment_method'],

                'Sub total' => strval($order['total_price']),

                'Shipping Price' => strval($order['shipping_rate']),
                'Discount' => strval($order['discount']),

                'Order Total' => strval($order['final_total_price']),

            ];
        }
        return $excel_data;
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


    private function prepareProducts($products){
        $products_items = [];
        foreach ($products as $product){
            $item = $product['name'] . ' ( ' . $product['quantity'] . ' )';
            $products_items [] = $item;
        }
        return collect($products_items)->implode("\n");
    }
}
