<?php

namespace Modules\Loyality\Helpers;

use Modules\Loyality\Repositories\CMS\LoyalityProgramRepository;
use Modules\Loyality\Repositories\Common\ProgramLevelRepository;
use Modules\Loyality\Repositories\Common\UserPointRepository;

class ValidationHelper
{
    protected $user_points_repo;


    public function __construct(UserPointRepository $user_points_repo)
    {
        $this->user_points_repo = $user_points_repo;
    }

    public function validateProgram($loyality_data, $level_id, $points_needed)
    {
        $response = [];
        $response['status'] = true;
        $response['data'] = [];

        if ($loyality_data !== null) {
            if ($loyality_data->is_levels && $level_id === null) {
                $response['status'] = false;
                $response['errors'] = ['program' => ['level required']];
            } elseif (!$loyality_data->is_levels && $points_needed === null) {
                $response['status'] = false;
                $response['errors'] = ['program' => ['points needed required']];
            }
        } else {
            $response['status'] = false;
            $response['errors'] = ['program' => ['No program yet']];
        }
        return $response;
    }

    public function validateUserPoints($user_id, $points_needed)
    {
        $response = [];
        $points = $this->user_points_repo->getPoints($user_id);

        if ($points_needed > $points['points']) {
            $response['status'] = false;
            $response['errors'] = ['points' => ['your points less than your needs']];
        } else {
            $response['status'] = true;
            $response['data'] = ['points' => $points];
        }
        return $response;
    }

    /**
     * @param $points_needed
     * @param $loyality_data
     * @param $products_price
     * @return array
     */
    public function validatePointsWithProgram($points_needed, $loyality_data, $products_price = null)
    {
        $response = [];

        $response['status'] = true;
        $response['data'] = [];

        ### In case Of levels or not
        if ($loyality_data->max_allowed_points < $points_needed) {
            $response['status'] = false;
            $response['errors'] = ['points' => [
                'the max allowed points is ' . $loyality_data->max_allowed_points . ' ,while the points needed is ' . $points_needed
            ]];
        } elseif ($loyality_data->min_allowed_points > $points_needed) {
            $response['status'] = false;
            $response['errors'] = ['points' => [
                'the min allowed points is ' . $loyality_data->min_allowed_points . ' ,while the points needed is ' . $points_needed
            ]];
        }

        ### In case if it is not levels only
        if (!$loyality_data->is_levels) {
            $price = $loyality_data->point_to_price * $points_needed;
            if ($price > $products_price) {
                $response['status'] = false;
                $response['errors'] = ['points' => ['the amount needed bigger than what should']];
            }
        }

        return $response;
    }

}
