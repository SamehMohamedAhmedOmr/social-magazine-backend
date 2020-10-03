<?php

namespace Modules\Users\Services\Frontend;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Repositories\AddressRepository;
use Modules\Users\Transformers\AddressResource;
use Modules\WareHouse\Repositories\DistrictRepository;

class AddressService extends LaravelServiceClass
{
    private $address_repo;
    private $districtRepository;

    public function __construct(
        AddressRepository $address,
        DistrictRepository $districtRepository
    )
    {
        $this->address_repo = $address;
        $this->districtRepository = $districtRepository;
    }

    public function index()
    {
        $conditions = ['user_id' => Auth::id()];
        if ((!request()->has('is_pagination')) || request('is_pagination')) {
            list($addresses, $pagination) = parent::paginate($this->address_repo, null, true, $conditions);
        } else {
            $addresses = $this->address_repo->all($conditions);
            $pagination = null;
        }
        $addresses->load('district.language', 'district.child', 'district.child.language');


        $addresses = AddressResource::collection($addresses);
        return ApiResponse::format(200, $addresses, null, $pagination);
    }

    public function show($id)
    {
        $address = $this->address_repo->get($id, ['user_id' => Auth::id()]);

        $address->load('district.language', 'district.child', 'district.child.language');

        $address = AddressResource::make($address);
        return ApiResponse::format(200, $address);
    }

    public function store()
    {
        $address_data = request()->all();

        if (!isset($address_data['user_id'])) {
            $user_id = Auth::id();
            $address_data['user_id'] = $user_id;
        } else {
            $user_id = $address_data['user_id'];
        }

        $district = $this->districtRepository->get($address_data['district_id']);

        if (!isset($address_data['city_id'])) {
            $city_id = ($district->parent_id) ? $district->parent_id : $district->id;
            $address_data['city_id'] = $city_id;
        }

        if (!isset($address_data['country_id'])) {
            $address_data['country_id'] = $district->country_id;
        }

        $address = $this->address_repo->create($address_data);

        $addresses = $this->address_repo->all([
            'user_id' => $user_id
        ]);

        $addresses->load('district.language', 'district.child', 'district.child.language');

        $addresses = AddressResource::collection($addresses);
        return ApiResponse::format(200, $addresses);
    }

    public function update($id)
    {
        $address_data = request()->all();

        if (isset($address_data['district_id'])){
            $district = $this->districtRepository->get($address_data['district_id']);

            if (!isset($address_data['city_id'])){
                $city_id = ($district->parent_id) ? $district->parent_id : $district->id;
                $address_data['city_id'] = $city_id;
            }

            if (!isset($address_data['country_id'])){
                $address_data['country_id'] = $district->country_id;
            }
        }

        $address = $this->address_repo->update($id, $address_data);

        $address->load('district.language', 'district.child', 'district.child.language');


        $address = AddressResource::make($address);
        return ApiResponse::format(200, $address);
    }

    public function delete($id)
    {
        $address = $this->address_repo->delete($id);
        return ApiResponse::format(200, $address, 'Address deleted successfully');
    }
}
