<?php

namespace Modules\Loyality\Services\Common;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Loyality\Jobs\PurchaseJob;
use Modules\Loyality\Transformers\Repo\PointsLogResource as PointsLogResourceRepo;

class PurchaseService extends LoyalityService
{
    public function calculate($request)
    {
        $data = $this->calculation($request->products);
        return ApiResponse::format(200, $data, 'Calculated Successfully');
    }


    public function save($request)
    {
        // Calculate Points from products
        $data = $this->calculation($request->products);
        $points = $data['points'];

        // Calculate Date
        $expiration_date = $this->loyality_program_data
            ? now()->addDays($this->loyality_program_data->days_until_expiration)
            : now();
        $refund_date = $this->loyality_program_data
            ? now()->addDays($this->loyality_program_data->days_until_refund)
            : now();

        // Create record
        $point_log = PointsLogResourceRepo::toArray(
            $request->order_id,
            $request->user_id,
            $expiration_date->format('Y-m-d H:i:s'),
            $refund_date->format('Y-m-d H:i:s'),
            $points,
            $data['money_spent']
        );
        $points_log = $this->points_logs_repo->create($point_log);

        ## Add points to user after refund date is passed
        PurchaseJob::dispatch(
            $request->user_id,
            $points,
            $this->user_point_repository,
            $this->points_logs_repo,
            $points_log->id,
            $expiration_date
        )
            ->delay($refund_date);

        // return User's Points
        return ApiResponse::format(
            201,
            $data,
            $points . ' points were added successfully and you can use them after ' .
            $this->loyality_program_data->days_until_refund . ' days'
        );
    }


    /**
     * @param $products_ids_prices
     * @return mixed
     */
    public function calculation($products_ids_prices)
    {
        $data = [];
        $loyality_program_data = $this->loyality_program_data;
        $price = $this->calculateProductsFinalPrice($products_ids_prices);
        $data['money_spent'] = $price['price'];
        $data['points'] = $this->calculatePoints($data['money_spent'], $loyality_program_data);
        $data['unhandled_products'] = $price['unhandled_products'];
        return $data;
    }

    /**
     * @param $products_ids_prices
     * @return array
     */
    public function calculateProductsFinalPrice($products_ids_prices)
    {
        $products_ids = [];
        $products_prices = [];
        $final_price = 0;

        foreach ($products_ids_prices as $id_price) {
            $products_ids[] = $id_price['id'];
            $products_prices[$id_price['id']] = $id_price['price'];
        }

        $products_weights = $this->loyality_product_repo->getWeights($products_ids);

        foreach ($products_weights as $product_id => $weight) {
            $final_price += ($products_prices[$product_id] * $weight / 100);
        }

        return ['price' => $final_price, 'unhandled_products' => count($products_ids_prices) - count($products_weights)];
    }

    /**
     * @param $price
     * @return float|int
     */
    public function calculatePoints($price, $loyality_program_data)
    {
        $points = 0;
        if ($loyality_program_data != null) {
            $points = $loyality_program_data->points_option == 'price'
                ? $price * $loyality_program_data->price_to_points
                : $price * $loyality_program_data->price_to_points / 100;
        }

        return floor($points);
    }
}
