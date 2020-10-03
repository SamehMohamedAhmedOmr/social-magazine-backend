<?php

namespace Modules\Catalogue\Repositories\Frontend;

use Modules\Catalogue\Repositories\Common\CategoryCommonRepository;

class CategoryRepository extends CategoryCommonRepository
{
    public function paginate(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by = 'desc',
        $filter_language = ''
    ) {
        $query = $this->model->join('category_language', 'categories.id', 'category_language.category_id');

        $query = $query->whereHas('parent', function ($q) {
            $q->where('is_active', true);
        })->orWhere('parent_id', null);

        $query = $query->where('categories.is_active', true)
            ->where('category_language.language_id', $filter_language);

        $query = $conditions != []
            ? $query->where($conditions)
            : $query;

        if (isset($search)){
            $query = $search['search_key'] !== null
                ? $query->where('category_language.name', 'LIKE', "%{$search['search_key']}%")
                : $query;
        }

        $query = $sort_by != 'name'
            ? $query->orderBy("categories.$sort_by", $order_by)
            : $query->orderBy('category_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $query->select('categories.*', 'category_language.name', 'category_language.slug');

        return $query->distinct('categories.id')->paginate($per_page);
    }

    public function loadRelations($object, $relations = [])
    {
        return $relations !== []
            ? $object->load($relations)
            : $object->load([
                'child' => function ($query) {
                    $query->where('is_active', true);
                },
                'child.currentLanguage'
            ]);
    }

    public function get($value, $conditions = [], $column = 'slug', $with = [])
    {
        $id = (integer)$value;

        if (!$id) {
            $this->category_language_model = $this->category_language_model->where($column, $value)->firstOrFail();
            $id = $this->category_language_model->category_id;
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where('parent_id', null)->orwhereHas('parent', function ($query) {
            $query->where('is_active', true)->where('deleted_at', null);
        })->where('id', $id)->firstOrFail();

        return $data->load([
            'parent.currentLanguage',
            'child' => function ($query) {
                $query->where('is_active', true);
            },
            'child.currentLanguage'
        ]);
    }
}
