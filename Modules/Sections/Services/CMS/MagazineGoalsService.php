<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\MagazineGoalsRepository;
use Modules\Sections\Transformers\CMS\MagazineGoalsResource;
use Throwable;

class MagazineGoalsService extends LaravelServiceClass
{

    private $magazineGoalsRepository;

    public function __construct(MagazineGoalsRepository $magazineGoalsRepository)
    {
        $this->magazineGoalsRepository = $magazineGoalsRepository;
    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($contents, $pagination) = parent::paginate($this->magazineGoalsRepository, null, true);
        } else {
            $contents = parent::list($this->magazineGoalsRepository, true);
        }

        $contents = MagazineGoalsResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }

    /**
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $goals =  $this->magazineGoalsRepository->create($request->all());

            $goals = MagazineGoalsResource::make($goals);
            return ApiResponse::format(201, $goals, 'Content Created!');
        });

    }

    public function show($id)
    {
        $goals = $this->magazineGoalsRepository->get($id);

        $goals = MagazineGoalsResource::make($goals);

        return ApiResponse::format(200, $goals);
    }

    public function update($id, $request = null)
    {
        $goals = $this->magazineGoalsRepository->update($id, $request->all());

        $goals = MagazineGoalsResource::make($goals);

        return ApiResponse::format(200, $goals,'Content Updated');
    }

    public function delete($id)
    {
        $goals = $this->magazineGoalsRepository->delete($id);
        return ApiResponse::format(200, $goals, 'Content Deleted!');
    }



}
