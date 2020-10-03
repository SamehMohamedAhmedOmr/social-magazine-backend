<?php


namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\TimeSectionRepository;
use Modules\Settings\Transformers\TimeSectionResource;

class TimeSectionService extends LaravelServiceClass
{
    private $timeSectionRepository;

    public function __construct(TimeSectionRepository $timeSectionRepository)
    {
        $this->timeSectionRepository = $timeSectionRepository;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($timeSection, $pagination) = parent::paginate($this->timeSectionRepository, getLang());
        } else {
            $timeSection = parent::list($this->timeSectionRepository);

            $pagination = null;
        }

        return ApiResponse::format(200, TimeSectionResource::collection($timeSection), 'time Section data retrieved successfully', $pagination);
    }

    public function store($request = null)
    {
        /* Save main time section Object */
        $timeSection = $this->timeSectionRepository->create($request->except('data', 'days'));
        $this->timeSectionRepository->syncObjectLanguages($request->data, $timeSection);
        $this->timeSectionRepository->syncDays($request->days, $timeSection);
        return ApiResponse::format(200, TimeSectionResource::make($timeSection), 'time Section Created successfully', null);
    }

    public function update($id, $request = null)
    {
        /* update main time section Object */
        $timeSection = $this->timeSectionRepository->update($id, $request->except('data', 'days'));
        if ($request->has('data')) {
            $this->timeSectionRepository->syncObjectLanguages($request->data, $timeSection);
        }
        if ($request->has('days')) {
            $this->timeSectionRepository->syncDays($request->days, $timeSection);
        }
        return ApiResponse::format(200, TimeSectionResource::make($timeSection), 'Time Section Updated successfully', null);
    }

    public function show($id)
    {
        $timeSection = $this->timeSectionRepository->get($id);
        return ApiResponse::format(200, TimeSectionResource::make($timeSection), 'Time Section retrieved successfully');
    }

    public function delete($id)
    {
        $this->timeSectionRepository->deleteTimeSection($id);
        return ApiResponse::format(200, [], 'time section deleted successfully');
    }
}
