<?php

namespace Modules\WareHouse\Repositories\CMS\Order;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Order\OrderStatus;
use Modules\WareHouse\Entities\Order\OrderStatusLanguage;

class OrderStatusRepository extends LaravelRepositoryClass
{
    protected $model;

    protected $orderStatusLanguage;

    public function __construct(OrderStatus $orderStatus, OrderStatusLanguage $orderStatusLanguage)
    {
        $this->model = $orderStatus;
        $this->orderStatusLanguage = $orderStatusLanguage;
    }

    public function index(
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $sort_language= ''
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
        $sort_language= ''
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
        $query = $this->model->join('order_status_language', 'order_statuses.id', 'order_status_language.order_status_id');

        $query = $search['is_active'] !== null
            ? $query->where('order_statuses.is_active', $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] === true
            ? $query->withTrashed()->where('brands.deleted_at', '!=', null)
            : $query;

        $query = $sort_by == 'name' ? $query->where('order_status_language.language_id', $sort_language) : $query;
        $query = $sort_by != 'name' ? $query->orderBy("order_statuses.$sort_by", $order_by)
            : $query->orderBy('order_status_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('order_statuses.*', 'order_status_language.name')
            : $query->select('order_statuses.*'); // because duplicaton


        return $query->distinct('order_statuses.id');
    }

    public function loadRelations($object, $relations = ['languages.language'])
    {
        return $object->load($relations);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['order_status_languages'];
        unset($attributes['order_status_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['order_status_id'] = $data->id;
        }

        $this->orderStatusLanguage->insert($name_slug);

        return $data->load('languages.language');
    }

    public function get($value, $conditions = [], $column = 'id', $with = ['languages.language'])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        return $data->load($with);
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $name_slug = null;
        if (array_key_exists('order_status_languages', $attributes)) {
            $name_slug = $attributes['order_status_languages'];
            unset($attributes['order_status_languages']);
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        /**
         * Update Brand Model
         */
        if ($attributes != []) {
            $data->update($attributes);
        }


        /**
         * Update Brand Language Model
         */
        if ($name_slug != null) {
            foreach ($name_slug as &$item) {
                $item['order_status_id'] = $data->id;
            }
            $data->languages()->delete();

            $this->orderStatusLanguage->insert($name_slug);
        }

        return $data->load('languages.language');
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
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->load('languages.language')->restore();
    }

    public function whereIn($keys)
    {
        return collect($this->model->whereIn('key', $keys)->select('id', 'key')->get()->toArray());
    }
}
