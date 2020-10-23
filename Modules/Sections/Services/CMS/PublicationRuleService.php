<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\PublicationRuleRepository;
use Modules\Sections\Transformers\CMS\PublicationRulesResource;
use Throwable;

class PublicationRuleService extends LaravelServiceClass
{
    private $publicationRuleRepository;

    public function __construct(PublicationRuleRepository $publicationRuleRepository)
    {
        $this->publicationRuleRepository = $publicationRuleRepository;
    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($contents, $pagination) = parent::paginate($this->publicationRuleRepository, null, true);
        } else {
            $contents = parent::list($this->publicationRuleRepository, true);
        }

        $contents = PublicationRulesResource::collection($contents);
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

            $content =  $this->publicationRuleRepository->create($request->all());

            CacheHelper::forgetCache(SectionsCache::publicationRule());

            $content = PublicationRulesResource::make($content);
            return ApiResponse::format(201, $content, 'Content Created!');
        });

    }

    public function show($id)
    {
        $content = $this->publicationRuleRepository->get($id);

        $content = PublicationRulesResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function update($id, $request = null)
    {
        $content = $this->publicationRuleRepository->update($id, $request->all());

        CacheHelper::forgetCache(SectionsCache::publicationRule());

        $content = PublicationRulesResource::make($content);

        return ApiResponse::format(200, $content,'Content Updated');
    }

    public function delete($id)
    {
        $content = $this->publicationRuleRepository->delete($id);
        CacheHelper::forgetCache(SectionsCache::publicationRule());
        return ApiResponse::format(200, $content, 'Content Deleted!');
    }

}
