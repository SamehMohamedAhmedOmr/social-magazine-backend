<?php

namespace Modules\Catalogue\Repositories\Frontend;

use Modules\Catalogue\Repositories\Common\VariantCommonRepository;

class VariantRepository extends VariantCommonRepository
{

    /**
     * @param int $per_page
     * @param array $conditions
     * @param null $search
     * @param string $sort_by
     * @param string $order_by
     * @param string $filter_language
     * @return mixed
     */
    public function paginate(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $filter_language = ''
    ) {
        $query = $this->model->join('variant_language', 'variants.id', 'variant_language.variant_id')
            ->join('variant_values', 'variants.id', 'variant_values.variant_id')
            ->where('variant_values.is_active', true)
            ->where('variants.is_active', true)
            ->where('variant_language.language_id', $filter_language);

        $query = $search['search_key'] !== null
            ? $query->where('variant_language.name', 'LIKE', "%{$search['search_key']}%")
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("variants.$sort_by", $order_by)
            : $query->orderBy('variant_language.name.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $query->select('variants.*', 'variant_language.name', 'variant_language.slug');

        return $query->distinct('variants.id')->paginate($per_page);
    }

    public function loadRelations($object, $relations = [])
    {
        return $relations != []
            ? $object->load($relations)
            :  $object->load([
                'values' => function ($query) {
                    $query->with('currentLanguage')->where('is_active', true)->where('deleted_at', null);
                }]);
    }

    public function get($value, $conditions = [], $column = 'slug', $with = [])
    {
        $id = (integer) $value;

        if (!$id) {
            $this->variant_language_model = $this->variant_language_model->where($column, $value)->firstOrFail();
            $id = $this->variant_language_model->brand_id;
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where('id', $id)
            ->whereHas([
                'values' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                }])
            ->firstOrFail();

        return $data->load([
            'currentLanguage',
            'values' => function ($query) {
                $query->with('currentLanguage')->where('is_active', true)->where('deleted_at', null);
            }]);
    }
}
