<?php

namespace Modules\Catalogue\Repositories\CMS;

use Illuminate\Support\Facades\Cache;
use Modules\Catalogue\Jobs\AddToCache;
use Modules\Catalogue\Jobs\RemoveFromCache;
use Modules\Catalogue\Repositories\Common\UnitOfMeasureCommonRepository;

class UnitOfMeasureRepository extends UnitOfMeasureCommonRepository
{
    public function index(
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $sort_language = ''
    ) {
        $query = $this->query(
            $search,
            $sort_by,
            $order_by,
            $sort_language
        );

        return $query->get();
    }

    public function paginate(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $sort_language = ''
    ) {
        $query = $this->query(
            $search,
            $sort_by,
            $order_by,
            $sort_language
        );

        return $query->paginate($per_page);
    }

    protected function query(
        $search,
        $sort_by,
        $order_by,
        $sort_language
    )
    {
        $query = $this->model->join('unit_of_measure_language', 'units_of_measure.id', 'unit_of_measure_language.unit_of_measure_id');

        $query = $search['is_active'] !== null
            ? $query->where('units_of_measure.is_active', $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] == true
            ? $query->withTrashed()->where('units_of_measure.deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('unit_of_measure_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('units_of_measure.id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("units_of_measure.$sort_by", $order_by)
            : $query->orderBy('unit_of_measure_language.name', $order_by);

        $query = $sort_by == 'name'
            ? $query->where('unit_of_measure_language.language_id', $sort_language)
            : $query;

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('units_of_measure.*', 'unit_of_measure_language.name')
            : $query->select('units_of_measure.*');

        return $query->distinct('units_of_measure.id');
    }

    public function create(array $attributes, $load = [])
    {
        $names = $attributes['unit_of_measure_languages'];
        unset($attributes['unit_of_measure_languages']);
        $data = $this->model->create($attributes);

        foreach ($names as &$item) {
            $item['unit_of_measure_id'] = $data->id;
        }

        $this->unit_of_measure_language_model->insert($names);
        $data = $this->get($data->id);

        return $data;
    }


    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();
        return $data;
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $names = null;
        if (array_key_exists('unit_of_measure_languages', $attributes)) {
            $names = $attributes['unit_of_measure_languages'];
            unset($attributes['unit_of_measure_languages']);
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where('id', $value)->withTrashed()->firstOrFail();

        /**
         * Update Unit Of Measure Model
         */
        if ($attributes != []) {
            $data->update($attributes);
        }

        /**
         * Update Unit Of Measure Language Model
         */
        if ($names != null) {
            foreach ($names as &$item) {
                $item['unit_of_measure_id'] = $data->id;
            }
            $data->languages()->delete();

            $this->unit_of_measure_language_model->insert($names);
        }
        return $this->get($value, $attributes, $conditions, $column);
        ;
    }

    public function delete($value, $conditions = [], $column = 'id')
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;
        $data = $data->where($column, $value)->firstOrFail();
        return $data->delete();
    }

    public function restore($value, $conditions = [], $column = 'id')
    {
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->restore();
    }
}
