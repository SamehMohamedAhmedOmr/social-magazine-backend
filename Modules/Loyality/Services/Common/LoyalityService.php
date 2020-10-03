<?php

namespace Modules\Loyality\Services\Common;

use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\Common\LoyalityProductsRepository;
use Modules\Loyality\Repositories\CMS\LoyalityProgramRepository;
use Modules\Loyality\Repositories\Common\PointsLogRepository;
use Modules\Loyality\Repositories\Common\UserPointRepository;
use Modules\WareHouse\Services\Frontend\CheckoutOrderService;

class LoyalityService extends LaravelServiceClass
{
    protected $loyality_product_repo;

    protected $loyality_program_repo;

    protected $points_logs_repo;

    protected $user_point_repository;

    protected $loyality_program_data;

    public function __construct(
        LoyalityProductsRepository $loyality_product_repo,
        LoyalityProgramRepository $loyality_program_repo,
        PointsLogRepository $points_logs_repo,
        UserPointRepository $user_point_repository
    )
    {
        $this->loyality_product_repo = $loyality_product_repo;
        $this->loyality_program_repo = $loyality_program_repo;
        $this->points_logs_repo = $points_logs_repo;
        $this->user_point_repository = $user_point_repository;
        $this->loyality_program_data = $this->loyality_program_repo->get(null, [], 'id', 'levels');
    }

}
