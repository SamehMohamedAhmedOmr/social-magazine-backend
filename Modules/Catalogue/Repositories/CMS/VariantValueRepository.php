<?php

namespace Modules\Catalogue\Repositories\CMS;

use Illuminate\Support\Facades\Cache;
use Modules\Catalogue\Jobs\AddToCache;
use Modules\Catalogue\Jobs\RemoveFromCache;
use Modules\Catalogue\Repositories\Common\VariantValueCommonRepository;

class VariantValueRepository extends VariantValueCommonRepository
{

    public function index($search = null, $sort_by = 'id', $order_by = 'desc')
    {
        $query = $this->query($search, $sort_by, $order_by);
        return $query->get();
    }

    public function paginate($per_page = 15, $conditions = [], $search = null, $sort_by = 'id', $order_by = 'desc')
    {
        $query = $this->query($search, $sort_by, $order_by);
        return $query->paginate($per_page);
    }

    protected function query(
        $search,
        $sort_by,
        $order_by
    )
    {
        $query = $this->model->newQuery();
        $query = $search['is_active'] !== null
            ? $query->where("is_active", $search['is_active'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] == true
            ? $query->withTrashed()->where('deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null ? $query->where(
            'value',
            'LIKE',
            '%' . $search['search_key'] . '%'
        ) : $query;

        $query = $search['variant_id'] ? $query->where('variant_id', $search['variant_id']) : $query;
        $query = $query->orderBy($sort_by, $order_by);

        return $query
            ->with(['languages.language', 'variant' => function ($query) {
                $query->without('values');
            }, 'variant.languages.language']);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['variant_value_languages'];
        unset($attributes['variant_value_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['variant_value_id'] = $data->id;
        }

        $this->variantValueLanguage->insert($name_slug);
        return $data->load('languages.language', 'variant', 'variant.languages.language');
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();
        return $data->load('languages.language', 'variant', 'variant.languages.language');
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        $data->update($attributes);

        return $data->load('languages.language', 'variant', 'variant.languages.language');
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
        return $this->model->where('id', $value)
            ->onlyTrashed()->firstOrFail()->load('languages.language', 'variant', 'variant.languages.language')->restore();
    }


    public function getSlugIfDuplication($slug, $variant_value_id = null)
    {
        $query = $this->variantValueLanguage->where('slug', $slug);
        return $variant_value_id ? $query->where('variant_value_id', '!=', $variant_value_id)->first() : $query->first();
    }
}
