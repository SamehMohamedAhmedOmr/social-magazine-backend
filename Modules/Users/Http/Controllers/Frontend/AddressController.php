<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\AddressRequest;
use Modules\Users\Services\Frontend\AddressService;

class AddressController extends Controller
{
    private $addressService;

    public function __construct(AddressService $address)
    {
        $this->addressService = $address;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->addressService->index();
    }

    /**
     * Show the specified resource.
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function show(AddressRequest $request)
    {
        return $this->addressService->show($request->address);
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
