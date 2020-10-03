<?php

namespace Modules\Loyality\Services\Common;

use Illuminate\Validation\ValidationException;
use Modules\Loyality\Repositories\CMS\LoyalityProgramRepository;
use Modules\Loyality\Repositories\Common\LoyalityProductsRepository;
use Modules\Loyality\Repositories\Common\PointsLogRepository;
use Modules\Loyality\Repositories\Common\UserPointRepository;
use Modules\WareHouse\Services\Frontend\CheckoutOrderService;

class LevelService
{
    protected $checkoutOrderService;

    protected $loyality_program_data;


    public function __construct(
        LoyalityProgramRepository $loyality_program_repo,
        CheckoutOrderService $checkoutOrderService
    )
    {
        $this->loyality_program_data = $loyality_program_repo->get(null, [], 'id', 'levels');
        $this->checkoutOrderService = $checkoutOrderService;
    }

    public function getLevels($request)
    {
        list($items, $warehouse , $actual_total_price ,
            $shipping_price, $shipping_rule_id,
            $discount, $vat, $final_total_price) = $this->checkoutOrderService->cartItemCalculation($request);

        $data = $this->availableLevels($final_total_price);
        $data['final_price_before_loyality'] = $final_total_price;
        return $data;
    }

    /**
     * @param $total_order_price
     * @return mixed
     * @throws ValidationException
     */
    public function availableLevels($total_order_price)
    {
        if (!$this->loyality_program_data->is_levels) {
            return [
                'is_levels' => false,
                'levels' => [],
            ];
        }

        $price_to_points = $total_order_price * $this->loyality_program_data->price_to_points;

        return [
            'is_levels' => true,
            'levels' => $this->loyality_program_data->levels()->select('id', 'points')
                ->where('points', '<=', $price_to_points)
                ->get()->toArray(),
        ];
    }
}
