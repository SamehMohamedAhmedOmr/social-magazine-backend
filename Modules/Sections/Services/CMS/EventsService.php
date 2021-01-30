<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\Facade\CacheHelper;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Facade\SectionsHelper;
use Modules\Sections\Repositories\EventsRepository;
use Modules\Sections\Transformers\CMS\EventsResource;
use Throwable;

class EventsService extends LaravelServiceClass
{
    protected $repository;

    public function __construct(EventsRepository $repository)
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

        $contents = EventsResource::collection($contents);
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

            $data = $request->all();
            $slug = UtilitiesHelper::generateSlug($data['title']);
            $data['slug'] = $slug;

            $news = $this->repository->getBySlug($slug,'slug');

            if ($news){
                SectionsHelper::duplicateNewsTitle();
            }

            $content =  $this->repository->create($data);

            if (isset($request->images)){
                $this->repository->attach($content, $request->images);
            }

            $this->forgetCache();

            $content->load([
                'images'
            ]);

            $content = EventsResource::make($content);
            return ApiResponse::format(201, $content, 'Content Created!');
        });

    }

    public function show($id)
    {
        $content = $this->repository->get($id);

        $content->load([
            'images'
        ]);

        $content = EventsResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function update($id, $request = null)
    {
        $data = $request->all();
        if (isset($request->title)){
            $slug = UtilitiesHelper::generateSlug($data['title']);
            $data['slug'] = $slug;

            $news = $this->repository->getBySlug($slug,'slug',[
                [
                    'id' , '!=' , $id
                ]
            ]);

            if ($news){
                SectionsHelper::duplicateNewsTitle();
            }
        }

        $content = $this->repository->update($id, $data);

        if (isset($request->images)){
            $this->repository->attach($content, $request->images);
        }

        $this->forgetCache();

        $content->load([
            'images'
        ]);

        $content = EventsResource::make($content);

        return ApiResponse::format(200, $content,'Content Updated');
    }

    public function delete($id)
    {
        $content = $this->repository->delete($id);
        $this->forgetCache();
        return ApiResponse::format(200, $content, 'Content Deleted!');
    }

    private function forgetCache(){
        CacheHelper::forgetCache(SectionsCache::events());
        CacheHelper::forgetCache(SectionsCache::latestEvents());
    }

}
