<?php

namespace Modules\Catalogue\Repositories\CMS;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Modules\Catalogue\Jobs\AddToCache;
use Modules\Catalogue\Jobs\RemoveFromCache;
use Modules\Catalogue\Repositories\Common\BrandCommonRepository;

class BrandRepository extends BrandCommonRepository
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
        $query = $this->model->join('brand_language', 'brands.id', 'brand_language.brand_id');

        $query = $search['is_active'] !== null
            ? $query->where('brands.is_active', $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] === true
            ? $query->withTrashed()->where('brands.deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('brand_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('brands.id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $sort_by == 'name' ? $query->where('brand_language.language_id', $sort_language) : $query;
        $query = $sort_by != 'name' ? $query->orderBy("brands.$sort_by", $order_by)
            : $query->orderBy('brand_language.name', $order_by);

        return $query->distinct('brands.id');
    }

    public function loadRelations($object, $relations = ['languages.language'])
    {
        return $object->load($relations);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['brand_languages'];
        unset($attributes['brand_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['brand_id'] = $data->id;
        }

        $this->brand_language_model->insert($name_slug);

        return $data->load('languages.language');
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        return $data->load('languages.language');
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $name_slug = null;
        if (array_key_exists('brand_languages', $attributes)) {
            $name_slug = $attributes['brand_languages'];
            unset($attributes['brand_languages']);
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
                $item['brand_id'] = $data->id;
            }
            $data->languages()->delete();

            $this->brand_language_model->insert($name_slug);
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

    public function getSlugIfDuplication($slug, $brand_id = null)
    {
        $query = $this->brand_language_model->where('slug', $slug);
        return $brand_id ? $query->where('brand_id', '!=', $brand_id)->first() : $query->first();
    }
}
