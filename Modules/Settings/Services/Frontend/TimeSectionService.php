<?php


namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\TimeSectionRepository;
use Modules\Settings\Transformers\DaysFrontResource;

class TimeSectionService extends LaravelServiceClass
{
    private $timeSectionRepository;

    public function __construct(TimeSectionRepository $timeSectionRepository)
    {
        $this->timeSectionRepository = $timeSectionRepository;
    }

    public function index()
    {
        $timeSection = $this->timeSectionRepository->getTimeSectionFront(getLang());
        return ApiResponse::format(200, DaysFrontResource::collection($timeSection), 'time Section data retrieved successfully', null);
    }
}
