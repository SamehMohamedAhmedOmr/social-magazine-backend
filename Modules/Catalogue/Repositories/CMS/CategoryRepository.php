<?php

namespace Modules\Catalogue\Repositories\CMS;

use Illuminate\Support\Facades\Cache;
use Modules\Catalogue\Jobs\AddToCache;
use Modules\Catalogue\Jobs\RemoveFromCache;
use Modules\Catalogue\Repositories\Common\CategoryCommonRepository;

class CategoryRepository extends CategoryCommonRepository
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

    public function query(
        $search,
        $sort_by,
        $order_by,
        $sort_language
    ) {
        $query = $this->model->join('category_language', 'categories.id', 'category_language.category_id');

        $query = $search['is_active'] !== null
            ? $query->where('categories.is_active', $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] === true
            ? $query->withTrashed()->where('categories.deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('category_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('categories.id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("categories.$sort_by", $order_by)
            : $query->orderBy('category_language.name', $order_by);

        $query = $sort_by == 'name' ? $query->where('category_language.language_id', $sort_language) : $query;

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('categories.*', 'category_language.name')
            : $query->select('categories.*'); // because duplicaton

        return $query->distinct('categories.id');
    }


    public function loadRelations($object, $relations = ['languages.language', 'parent'])
    {
        return $object->load($relations);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['category_languages'];
        unset($attributes['category_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['category_id'] = $data->id;
        }

        $this->category_language_model->insert($name_slug);
        return $data;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        return $data->load('languages.language', 'parent');
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $name_slug = null;
        if (array_key_exists('category_languages', $attributes)) {
            $name_slug = $attributes['category_languages'];
            unset($attributes['category_languages']);
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();


        /**
         * Update Category Model
         */
        if ($attributes != []) {
            $data->update($attributes);
        }

        /**
         * Update Category Language Model
         */
        if ($name_slug != null) {
            foreach ($name_slug as &$item) {
                $item['category_id'] = $data->id;
            }
            $data->languages()->delete();
            $this->category_language_model->insert($name_slug);
        }

        return $data->load('languages.language', 'parent');
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
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->load('languages.language', 'parent')->restore();
    }

    public function getSlugIfDuplication($slug, $category_id)
    {
        $query = $this->category_language_model->where('slug', $slug);
        return $category_id ? $query->where('category_id', '!=', $category_id)->first() : $query->first();
    }

    public function pluckIdsFromSlugs($slugs)
    {
        return $this->category_language_model->whereIn('slug', $slugs)->distinct()->pluck('id')->toArray();
    }
}
