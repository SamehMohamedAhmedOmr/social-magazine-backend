<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\UnitOfMeasure;
use Modules\Catalogue\Entities\UnitOfMeasureLanguage;

class UnitOfMeasureCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $unit_of_measure_language_model;

    public function __construct(UnitOfMeasure $unit_of_measure_model, UnitOfMeasureLanguage $unit_of_measure_language_model)
    {
        $this->model = $unit_of_measure_model;
        $this->unit_of_measure_language_model = $unit_of_measure_language_model;
        $this->cache_key = 'unit_of_measure';
    }

    public function loadRelations($object, $relations = ['languages.language'])
    {
        return $object->load($relations);
    }
}
