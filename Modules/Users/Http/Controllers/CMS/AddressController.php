<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\AddressRequest;
use Modules\Users\Http\Requests\CMS\AddressCMSRequest;
use Modules\Users\Http\Requests\CMS\AddressUserIDRequest;
use Modules\Users\Services\CMS\AddressCMSService;
use Modules\Users\Services\Frontend\AddressService;

class AddressController extends Controller
{
    private $addressService;
    private $addressCMSService;

    public function __construct(AddressService $address, AddressCMSService $addressCMSService)
    {
        $this->addressService = $address;
        $this->addressCMSService = $addressCMSService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @param AddressUserIDRequest $addressUserIDRequest
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, AddressUserIDRequest $addressUserIDRequest)
    {
        return $this->addressCMSService->index();
    }

    /**
     * Show the specified resource.
     * @param AddressCMSRequest $request
     * @return JsonResponse
     */
    public function show(AddressCMSRequest $request)
    {
        return $this->addressCMSService->show($request->address);
    }

    /**
     * Store a newly created resource in storage.
     * @param AddressRequest $request
     * @return void
     */
    public function store(AddressRequest $request)
    {
        return $this->addressService->store();
    }

    /**
     * Update the specified resource in storage.
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function update(AddressRequest $request)
    {
        return $this->addressService->update($request->address);
    }

    /**
     * Remove the specified resource from storage.
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function destroy(AddressRequest $request)
    {
        return $this->addressService->delete($request->address);
    }
}
