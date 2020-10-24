<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\TestimonialRequest;
use Modules\Sections\Services\CMS\TestimonialService;
use Throwable;

class TestimonialController extends Controller
{
    private $service;

    public function __construct(TestimonialService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param TestimonialRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(TestimonialRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param TestimonialRequest $request
     * @return JsonResponse|void
     */
    public function show(TestimonialRequest $request)
    {
        return $this->service->show($request->testimonial);
    }

    /**
     * Update the specified resource in storage.
     * @param TestimonialRequest $request
     * @return JsonResponse|void
     */
    public function update(TestimonialRequest $request)
    {
        return $this->service->update($request->testimonial, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param TestimonialRequest $request
     * @return JsonResponse|void
     */
    public function destroy(TestimonialRequest $request)
    {
        return $this->service->delete($request->testimonial);
    }
}
