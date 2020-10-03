<?php

namespace Modules\Catalogue\Repositories\CMS;

use Modules\Catalogue\Repositories\Common\ToppingMenuCommonRepository;

class ToppingMenuRepository extends ToppingMenuCommonRepository
{

    public function index(
        $search = null,
        $sort_by = 'id',
        $order_by = 'desc',
        $sort_language = ''
    )
    {
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
        $order_by = 'desc',
        $sort_language = ''
    )
    {
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
        $query = $this->model->join('topping_menu_language', 'topping_menus.id', 'topping_menu_language.topping_menu_id');

        $query = $search['is_active'] !== null
            ? $query->where('topping_menus.is_active', $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] == true
            ? $query->withTrashed()->where('topping_menus.deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('topping_menu_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('topping_menus.id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $sort_by == 'name'
            ? $query->where('topping_menu_language.language_id', $sort_language)
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("topping_menus.$sort_by", $order_by)
            : $query->orderBy('topping_menu_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('topping_menus.*', 'topping_menu_language.name')
            : $query->select('topping_menus.*'); // because duplicaton

        return $query->distinct('topping_menus.id');
    }

    public function loadRelations($object, $relations = ['languages.language'])
    {
        return $object->load($relations);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['topping_languages'];
        unset($attributes['topping_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['topping_menu_id'] = $data->id;
        }

        $this->topping_language_model->insert($name_slug);

        if (array_key_exists('products', $attributes)) {
            $data->products()->sync($attributes['products']);
        }

        return $data->load([
            'languages.language',
            'products.languages.language']);
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail()
            ->load([
                'languages.language',
                'products.languages.language']);

        return $data;
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $name_slug = null;
        if (array_key_exists('topping_languages', $attributes)) {
            $name_slug = $attributes['topping_languages'];
            unset($attributes['topping_languages']);
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        /**
         * Update Brand Model
         */

        if (array_key_exists('products', $attributes)) {
            $data->products()->sync($attributes['products']);
            unset($attributes['products']);
        }


        if ($attributes != []) {
            $data->update($attributes);
        }

        /**
         * Update Brand Language Model
         */
        if ($name_slug != null) {
            foreach ($name_slug as &$item) {
                $item['topping_menu_id'] = $data->id;
            }
            $data->languages()->delete();
            $this->topping_language_model->insert($name_slug);
        }
        return $data->load(
            'languages.language',
            'products.languages.language'
        );
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
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->load([
            'languages.language',
            'products.languages.language'])->restore();
    }

    public function getSlugIfDuplication($slug, $topping_menu_id = null)
    {
        $query = $this->topping_language_model->where('slug', $slug);
        return $topping_menu_id ? $query->where('topping_menu_id', '!=', $topping_menu_id)->first() : $query->first();
    }
}
