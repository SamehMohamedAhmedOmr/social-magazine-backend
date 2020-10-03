<?php

namespace Modules\Gallery\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Gallery\Http\Requests\GalleryRequest;
use Modules\Gallery\Services\CMS\GalleryService;

class GalleryController extends Controller
{
    private $galleryService;
    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->galleryService->all();
    }

    /**
     * store Resource
     * @param GalleryRequest $request
     * @return JsonResponse
     */
    public function store(GalleryRequest $request)
    {
        return  $this->galleryService->uploadImage($request->image, $request->gallery_type);
    }


    /**
     * Remove the specified resource from storage.
     * @param GalleryRequest $request
     * @return void
     */
    public function destroy(GalleryRequest $request)
    {
        return  $this->galleryService->delete($request->gallery_id);
    }
}
