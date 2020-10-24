<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineCategoryRepository;
use Modules\Sections\Transformers\CMS\MagazineCategoryResource;
use Throwable;

class MagazineCategoryService extends LaravelServiceClass
{
    protected $repository;

    public function __construct(MagazineCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($contents, $pagination) = parent::paginate($this->repository, null, true);
        } else {
            $contents = parent::list($this->repository, true);
        }

        $contents->load([
            'images'
        ]);

        $contents = MagazineCategoryResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }

    /**
     * Handles Add New CMSUser
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $content =  $this->repository->create($request->all());

            $this->repository->attach($content, $request->images);

            CacheHelper::forgetCache(SectionsCache::magazineCategory());

            $content->load([
                'images'
            ]);
            $content = MagazineCategoryResource::make($content);
            return ApiResponse::format(201, $content, 'Content Created!');
        });

    }

    public function show($id)
    {
        $content = $this->repository->get($id);

        $content->load([
            'images'
        ]);

        $content = MagazineCategoryResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function update($id, $request = null)
    {
        $content = $this->repository->update($id, $request->all());

        if (isset($request->images)){
            $this->repository->attach($content, $request->images);
        }

        CacheHelper::forgetCache(SectionsCache::magazineCategory());

        $content->load([
            'images'
        ]);

        $content = MagazineCategoryResource::make($content);

        return ApiResponse::format(200, $content,'Content Updated');
    }

    public function delete($id)
    {
        $content = $this->repository->delete($id);
        CacheHelper::forgetCache(SectionsCache::magazineCategory());
        return ApiResponse::format(200, $content, 'Content Deleted!');
    }

}
