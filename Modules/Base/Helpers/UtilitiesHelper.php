<?php

namespace Modules\Base\Helpers;

use Auth;
use Illuminate\Support\Arr;
use Modules\Users\Repositories\AddressRepository;
use Modules\WareHouse\Entities\Country;
use Request;

class UtilitiesHelper
{
    /**
     * @var object
     */
    private $address_repository;

    /**
     * Init new object.
     *
     * @param   AddressRepository  $address_repository
     */
    public function __construct(AddressRepository $address_repository)
    {
        $this->address_repository = $address_repository;
    }
    /**
     * Get district id from header or the logged in user.
     *
     * @return  integer
     */
    public function getDistrictId()
    {
        return Arr::get(getallheaders(), 'district-id', false) ?: $this->getUserDistrict(Auth::user());
    }

    /**
     * Get the given user district id.
     *
     * @param   User  $user
     *
     * @return  integer
     */
    public function getUserDistrict($user)
    {
        $district_id = 0;
        if ($user) {
            try {
                $address = $this->address_repository->get($user->id, ['is_active' => 1], 'user_id');
                $district_id = $address->district_id;
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            }
        }
        return $district_id;
    }

    public function prepareDeviceHeader()
    {
        $device_id = trim(Request::header('device_id'));
        $device_os = trim(Request::header('device_os'));
        $app_version = trim(Request::header('app_version'));

        return [
            (string)$device_id,
            (string)$device_os,
            (string)$app_version
        ];
    }

    public function getCountryId()
    {
        if (!request()->has('country_id')) {
            $country_id = \Session::get('country_id');
        } else {
            $country_id = request('country_id');
        }
        return $country_id;
    }

    public function projectSlug(){
        $project_folder = \Session::get(\Session::get('current_sub_domain'));
        return isset($project_folder) ? $project_folder['slug'] : '8000';
    }
}
