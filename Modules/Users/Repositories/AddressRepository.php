<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\Address;

class AddressRepository extends LaravelRepositoryClass
{
    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function createAddress($districtRepository, $user)
    {
        // Create Address Record

        $district = $districtRepository->get(request('district_id'));

        $country_id = request('country_id');
        $city_id = request('city_id');

        if (!$country_id) {
            $city_id = ($district->parent_id) ? $district->parent_id : $district->id;
        }

        if (!$city_id) {
            $country_id = $district->country_id;
        }

        $address_data = [
            // Required data
            'country_id' => $country_id,
            'city_id' => $city_id,

            'title' => request('title'),
            'street' => request('street'),
            'district_id' => request('district_id'),
            'nearest_landmark' => request('nearest_landmark'),
            'user_id' => $user->id,

            // Nullable data
            'address_phone' => request('address_phone', null),
            'building_no' => request('building_no', null),
            'apartment_no' => request('apartment_no', null),
            'floor_no' => request('floor_no', null),
            'lat' => request('lat', null),
            'lng' => request('lng', null),
        ];

        $this->create($address_data);
    }
}
