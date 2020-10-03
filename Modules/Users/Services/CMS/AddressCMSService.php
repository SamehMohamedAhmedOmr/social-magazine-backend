<?php

namespace Modules\Users\Services\CMS;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Repositories\AddressRepository;
use Modules\Users\Transformers\AddressResource;
use Modules\WareHouse\Repositories\DistrictRepository;

class AddressCMSService extends LaravelServiceClass
{
    private $address_repo;

    public function __construct(AddressRepository $address)
    {
        $this->address_repo = $address;
    }

    public function index()
    {
        $conditions = ['user_id' => request('user_id')];
        if ((!request()->has('is_pagination')) || request('is_pagination')) {
            list($addresses, $pagination) = parent::paginate($this->address_repo, null, true, $conditions);
        } else {
            $addresses = $this->address_repo->all($conditions);
            $pagination = null;
        }
        $addresses->load('district.language');


        $addresses = AddressResource::collection($addresses);
        return ApiResponse::format(200, $addresses, null, $pagination);
    }

    public function show($id)
    {
        $address = $this->address_repo->get($id);

        $address->load('district.language');

        $address = AddressResource::make($address);
        return ApiResponse::format(200, $address);
    }
}
