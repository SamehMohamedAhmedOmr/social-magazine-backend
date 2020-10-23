<?php

namespace Modules\Sections\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Sections\Entities\Tracker;

class TrackerRepository extends LaravelRepositoryClass
{
    public function __construct(Tracker $tracker)
    {
        $this->model = $tracker;
    }

    public function store(){
        $this->model->hit();
    }

    public function count(){
        $trackers =$this->model->get();
        return $trackers->count();
    }


}
