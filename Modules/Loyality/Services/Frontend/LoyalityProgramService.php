<?php

namespace Modules\Loyality\Services\Frontend;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\CMS\LoyalityProgramRepository;
use Modules\Loyality\Repositories\Common\UserPointRepository;
use Modules\Loyality\Services\Common\PurchaseService;
use Modules\Loyality\Services\Common\RedeemService;
use Modules\Loyality\Transformers\Frontend\LoyalityProgramResource;

class LoyalityProgramService extends LaravelServiceClass
{
    protected $loyality_program_repo;

    protected $purchaseService;

    protected $redeemService;

    protected $userPointRepository;

    public function __construct(LoyalityProgramRepository $loyality_program_repo,
                                PurchaseService $purchaseService,
                                RedeemService $redeemService,
                                UserPointRepository $userPointRepository)
    {
        $this->loyality_program_repo = $loyality_program_repo;
        $this->purchaseService = $purchaseService;
        $this->redeemService = $redeemService;
        $this->userPointRepository = $userPointRepository;
    }

    public function show($id = null)
    {
        $program_data = $this->loyality_program_repo->get(null);
        $userPoints = $this->userPointRepository->getPoints(Auth::id());

        $data = [
            'price_to_get' => $this->purchaseService->calculatePoints(1, $program_data),
            'points_to_redeem' => 1000,
            'price_to_redeem' => $this->redeemService->calculation(1000)['money_saved'],
            'user_points' => $userPoints,
            'available_user_points_price' => $this->redeemService->calculation($userPoints['points'])['money_saved'],
            'user_redeemed_points_price' => $this->userPointRepository->sumPointsRedeemed(Auth::id())
        ];

        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
