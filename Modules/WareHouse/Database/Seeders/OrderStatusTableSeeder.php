<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\DbHelper;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DbHelper::insertOrUpdate('order_statuses',[
            ['is_active' => true, 'key' => 'PENDING'],
            ['is_active' => true, 'key' => 'IN_SHIPMENT'],
            ['is_active' => true, 'key' => 'CANCELLED'],
            ['is_active' => true, 'key' => 'CONFIRMED'],
            ['is_active' => true, 'key' => 'DELIVERED'],
            ['is_active' => true, 'key' => 'PENDING_CREDIT'],
        ]);

        DbHelper::insertOrUpdate('order_status_language',[
            ['order_status_id' => 1, 'language_id' => 1, 'name' => 'Pending'],
            ['order_status_id' => 1, 'language_id' => 2, 'name' => 'قيد الانتظار'],
            ['order_status_id' => 2, 'language_id' => 1, 'name' => 'In Shipping'],
            ['order_status_id' => 2, 'language_id' => 2, 'name' => 'في الشحن'],
            ['order_status_id' => 3, 'language_id' => 1, 'name' => 'Cancelled'],
            ['order_status_id' => 3, 'language_id' => 2, 'name' => 'ألغيت'],
            ['order_status_id' => 4, 'language_id' => 1, 'name' => 'Confirmed'],
            ['order_status_id' => 4, 'language_id' => 2, 'name' => 'تم تأكيد'],
            ['order_status_id' => 5, 'language_id' => 1, 'name' => 'Delivered'],
            ['order_status_id' => 5, 'language_id' => 2, 'name' => 'تم التوصيل'],
            ['order_status_id' => 6, 'language_id' => 1, 'name' => 'Pending On Credit'],
            ['order_status_id' => 6, 'language_id' => 2, 'name' => 'معلقة على الائتمان'],
        ]);
    }
}
