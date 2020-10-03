<?php

namespace Modules\Users\Services\CMS;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\ResponseShape\ExportResource;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\ExcelExports\ClientExport;
use Modules\Users\Repositories\AddressRepository;
use Modules\Users\Repositories\ClientRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\ClientResource;
use Modules\Users\Transformers\CMS\UserOrdersResource;
use Modules\WareHouse\Repositories\DistrictRepository;
use Throwable;

class ClientService extends LaravelServiceClass
{
    private $user_repo;
    private $clientRepository;
    private $addressRepository;
    private $districtRepository;
    protected $client_type = 2;

    public function __construct(UserRepository $user_repo,
                                AddressRepository $addressRepository,
                                DistrictRepository $districtRepository,
                                ClientRepository $clientRepository)
    {
        $this->user_repo = $user_repo;
        $this->clientRepository = $clientRepository;

        $this->addressRepository = $addressRepository;
        $this->districtRepository = $districtRepository;

    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($users, $pagination) = parent::paginate($this->clientRepository, null, true, [
                'user_type' => $this->client_type
            ]);
        } else {
            $users = parent::list($this->clientRepository, true, [
                'user_type' => $this->client_type
            ]);
        }

        if (request('get_address')) {
            $users->load('address');
        }

        if (request('get_orders')) {
            $users->load('orders');
        }

        $users = ClientResource::collection($users);
        return ApiResponse::format(200, $users, null, $pagination);
    }

    /**
     * Handles Add New Admin
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {
            $user_data = $request->all();
            $user_data['user_type'] = $this->client_type;

            if (isset($user_data['password'])){
                $user_data['password'] = bcrypt($user_data['password']);
            }
            else{
                $user_data['password'] = bcrypt($this->randomPassword());
            }

            $user =  $this->user_repo->create($user_data);

            $this->clientRepository->create([
                'user_id' => $user->id,
                'phone' => $request->phone
            ]);

            if (isset($user_data['address'])){
                $address_data = $user_data['address'];
                $district = $this->districtRepository->get($address_data['district_id']);

                if (!isset($address_data['city_id'])) {
                    $city_id = ($district->parent_id) ? $district->parent_id : $district->id;
                    $address_data['city_id'] = $city_id;
                }

                if (!isset($address_data['country_id'])) {
                    $address_data['country_id'] = $district->country_id;
                }

                $address_data['user_id'] = $user->id;

                $this->addressRepository->create($address_data);
            }

            $user = ClientResource::make($user);
            return ApiResponse::format(201, $user, 'Researcher Added!');
        });

    }

    public function show($id)
    {
        $user = $this->user_repo->get($id, [
            'user_type' => $this->client_type
        ]);

        if (request('get_address')) {
            $user->load('address');
        }

        if (request('get_orders')) {
            $user->load('orders');
        }

        $user = ClientResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function update($id, $request = null)
    {
        $user = $this->user_repo->update($id, $request->only('name', 'email', 'is_active'));

        if ($request->phone) {
            $this->clientRepository->update($user->id, [
                'phone' => $request->phone
            ], [], 'user_id');
        }

        $user = ClientResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function delete($id)
    {
        $user = $this->user_repo->delete($id);
        return ApiResponse::format(200, $user, 'Researcher Deleted!');
    }

    public function clientOrders($id){

        $user = $this->user_repo->get($id);

        $orders = $user->orders;

        list($orders_count, $orders_total_price) = $this->calculateTotalAndPrice($orders);


        $user_order_resource =  new UserOrdersResource(
            $orders_count,
            $orders_total_price
        );

        return ApiResponse::format(200, $user_order_resource);
    }

    public function calculateTotalAndPrice($orders)
    {
        $count = $orders->count();

        $total_price = 0;

        foreach ($orders as $order) {
            $total_order_price = ($order->total_price + $order->shipping_price + $order->vat) - $order->discount;

            $total_price += $total_order_price;
        }

        return [$count, $total_price];
    }

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Customers', \App::make(ClientExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
