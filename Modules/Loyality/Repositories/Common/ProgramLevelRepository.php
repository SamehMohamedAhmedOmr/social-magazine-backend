<?php

namespace Modules\Loyality\Repositories\Common;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\ProgramLevel;

class ProgramLevelRepository extends LaravelRepositoryClass
{
    public function __construct(ProgramLevel $programLevel)
    {
        $this->model = $programLevel;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        return $this->model->where($column, $value)->first();
    }
}
