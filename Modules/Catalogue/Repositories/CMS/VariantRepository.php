<?php

namespace Modules\Catalogue\Repositories\CMS;

use Modules\Catalogue\Repositories\Common\VariantCommonRepository;

class VariantRepository extends VariantCommonRepository
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
        $query = $this->model->join('variant_language', 'variants.id', 'variant_language.variant_id');

        $query = $search['is_active'] !== null
            ? $query->where('variants.is_active', $search['is_active'])
            : $query;

        $query = $search['is_color'] !== null
            ? $query->where('variants.is_color', $search['is_color'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] == true
            ? $query->withTrashed()->where('variants.deleted_at', '!=', null)
            : $query;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('variant_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('variants.id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("variants.$sort_by", $order_by)
            : $query->orderBy("variant_language.name", $order_by);

        $query = $sort_by == 'name' ? $query->where('variant_language.language_id', $sort_language) : $query;

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('variants.*', 'variant_language.name')
            : $query->select('variants.*'); // because duplicaton

        return $query->distinct('variants.id');
    }

    public function loadRelations($object, $relations = ['languages.language', 'values'])
    {
        return $object->load($relations);
    }

    public function create(array $attributes, $load = [])
    {
        $name_slug = $attributes['variant_languages'];
        unset($attributes['variant_languages']);
        $data = $this->model->create($attributes);

        foreach ($name_slug as &$item) {
            $item['variant_id'] = $data->id;
        }

        $this->variant_language_model->insert($name_slug);
        return $data->load(['languages.language', 'values']);
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();
        return $data->load(['languages.language', 'values']);
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $name_slug = null;
        if (array_key_exists('variant_languages', $attributes)) {
            $name_slug = $attributes['variant_languages'];
            unset($attributes['variant_languages']);
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
                $item['variant_id'] = $data->id;
            }

            $data->languages()->delete();
            $this->variant_language_model->insert($name_slug);
        }
        return $data->load(['languages.language', 'values']);
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
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->load(['languages.language', 'values'])
            ->restore();
    }

    public function getSlugIfDuplication($slug, $variant_id = null)
    {
        $query = $this->variant_language_model->where('slug', $slug);
        return $variant_id ? $query->where('variant_id', '!=', $variant_id)->first() : $query->first();
    }
}
