<?php

namespace Modules\WareHouse\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalogue\Entities\Product;
use Modules\Users\Entities\User;
use Modules\WareHouse\Entities\Country;
use Modules\WareHouse\Entities\Order\OrderStatus;
use Modules\WareHouse\Entities\Warehouse;
use Modules\WareHouse\Repositories\OrderItemRepository;
use Modules\WareHouse\Repositories\OrderRepository;

class OrderSeederTableSeeder extends Seeder
{
    private $orderRepository, $orderItemRepository;

    public function __construct(OrderRepository $orderRepository,  OrderItemRepository $orderItemRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderStatuses = OrderStatus::pluck('id')->toArray();
        $country = Country::where('country_code', config('base.default_country'))->first();

        Model::unguard();
        for($i = 0 ; $i < 50; $i++){
            $user = User::where('user_type',2)->inRandomOrder()->first();

            if($user) {
                $warehouses = Warehouse::withoutGlobalScopes()->where('country_id',64)->get();
                $warehouse = $warehouses->shuffle()->first();
                $order = $this->orderRepository->create([
                    'order_status_id' => $orderStatuses[array_rand($orderStatuses)],
                    'delivery_date' => Carbon::now()->subMinutes(rand(1, 55)),
                    'shipping_price' => rand(0,20),
                    'total_price' => rand(100,20000),
                    'discount' => rand(0,100),
                    'vat' => rand(0,100),
                    'device_id' => 1,
                    'user_id' => $user->id,
                    'payment_method_id' => rand(1,2),
                    'address_id' => 1,
                    'warehouse_id' => $warehouse->id,
                    'country_id' => $country->id
                ]);

                for ($j =0; $j < 5 ; $j++){
                    $products = Product::all();

                    $product = $products->shuffle()->first();

                    $this->orderItemRepository->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1,10),
                        'price' => rand(10,1000),
                        'has_toppings' => 0,
                    ]);
                }
            }

        }

    }
}
