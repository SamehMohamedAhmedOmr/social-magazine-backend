<?php


namespace Modules\FrontendUtilities\Services\CMS;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FrontendUtilities\ExcelExports\BannersExport;
use Modules\FrontendUtilities\Repositories\BannerRepository;
use Modules\FrontendUtilities\Transformers\BannerResource;

class BannerService extends LaravelServiceClass
{
    private $banner_repository;

    public function __construct(BannerRepository $banner_repository)
    {
        $this->banner_repository = $banner_repository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($banners, $pagination) = parent::paginate($this->banner_repository, \Session::get('language_id'));
        } else {
            $banners = parent::list($this->banner_repository, true);

            $pagination = null;
        }

        $banners->load([
            'currentLanguage',
            'bannerImg.galleryType'
        ]);

        return ApiResponse::format(200, BannerResource::collection($banners), 'banner data retrieved successfully', $pagination);
    }

    public function store($request = null)
    {
        /* prepare object for store */
        $requestData = $request->all();
        /* Save main banner Object */
        $banner = $this->banner_repository->create($requestData);

        /* save multi language for banner object*/
        $this->banner_repository->asyncObjectLanguages($request->data, $banner);

        $banner->load([
           'language',
           'bannerImg.galleryType'
        ]);

        return ApiResponse::format(200, BannerResource::make($banner), 'banner created successfully');
    }

    public function update($id, $request = null)
    {
        /* update main banner Object */
        $banner = $this->banner_repository->update($id, $request->except('data'));

        /* save multi language for banner object*/
        if (isset($request->data)) {
            $this->banner_repository->updateObjectLanguages($request->data, $banner);
        }

        $banner->load([
            'language',
            'bannerImg.galleryType'
        ]);

        return ApiResponse::format(200, BannerResource::make($banner), 'banner updated successfully');
    }

    public function delete($id)
    {
        $this->banner_repository->delete($id);
        return ApiResponse::format(200, [], 'banner deleted successfully');
    }

    public function restore($id)
    {
        $this->banner_repository->restore($id);
        return ApiResponse::format(200, [], 'banner restored successfully');
    }

    public function show($id)
    {
        $banner = $this->banner_repository->get($id);

        $banner->load([
            'language',
            'bannerImg.galleryType'
        ]);

        return ApiResponse::format(200, BannerResource::make($banner), 'banner data retrieved successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Banners', \App::make(BannersExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
