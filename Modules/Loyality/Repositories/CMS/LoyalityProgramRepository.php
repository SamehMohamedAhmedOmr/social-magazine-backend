<?php

namespace Modules\Loyality\Repositories\CMS;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\LoyalityProgram;

class LoyalityProgramRepository extends LaravelRepositoryClass
{
    public function __construct(LoyalityProgram $loyality_program)
    {
        $this->model = $loyality_program;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        return $this->model->first();
    }


    public function updateOrCreate(array $attributes)
    {
        $program = $this->model->updateOrCreate([], $attributes);
        if (isset($attributes['levels']) && $attributes['is_levels']) {
            $levels = array_map(function ($points){
                return ['points' => $points];
            }, $attributes['levels']);
            $program->levels()->delete();
            $program->levels()->createMany($levels);
        }
        return $program->load('levels');
    }
}
