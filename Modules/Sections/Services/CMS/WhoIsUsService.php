<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\CMS\WhoIsUsResource;
use Modules\Users\Transformers\CMS\AccountResource;
use Throwable;

class WhoIsUsService extends LaravelServiceClass
{
    private $whoIsUsRepository;

    public function __construct(WhoIsUsRepository $whoIsUsRepository)
    {
        $this->whoIsUsRepository = $whoIsUsRepository;
    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($contents, $pagination) = parent::paginate($this->whoIsUsRepository, null, true);
        } else {
            $contents = parent::list($this->whoIsUsRepository, true);
        }

        $contents = WhoIsUsResource::collection($contents);
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

            $whoIsUs =  $this->whoIsUsRepository->create($request->all());

            $whoIsUs = WhoIsUsResource::make($whoIsUs);
            return ApiResponse::format(201, $whoIsUs, 'Content Created!');
        });

    }

    public function show($id)
    {
        $whoIsUs = $this->whoIsUsRepository->get($id);

        $whoIsUs = WhoIsUsResource::make($whoIsUs);

        return ApiResponse::format(200, $whoIsUs);
    }

    public function update($id, $request = null)
    {
        $whoIsUs = $this->whoIsUsRepository->update($id, $request->all());

        $whoIsUs = WhoIsUsResource::make($whoIsUs);

        return ApiResponse::format(200, $whoIsUs,'Content Updated');
    }

    public function delete($id)
    {
        $whoIsUs = $this->whoIsUsRepository->delete($id);
        return ApiResponse::format(200, $whoIsUs, 'Content Deleted!');
    }

}
