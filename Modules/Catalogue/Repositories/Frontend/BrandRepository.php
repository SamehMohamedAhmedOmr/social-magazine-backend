<?php

namespace Modules\Catalogue\Repositories\Frontend;

use Modules\Catalogue\Repositories\Common\BrandCommonRepository;

class BrandRepository extends BrandCommonRepository
{
    /**
     * @param int $per_page
     * @param array $conditions
     * @param null $search
     * @param string $sort_by
     * @param string $order_by
     * @param string $filer_language
     * @return mixed
     */
    public function paginate(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $filer_language = ''
    ) {
        $query = $this->model->join('brand_language', 'brands.id', 'brand_language.brand_id')
            ->where('brands.is_active', true)
            ->where('brand_language.language_id', $filer_language);


        if (isset($search)){
            $query = $search['search_key'] != null
                ? $query->where('brand_language.name', 'LIKE', "%{$search['search_key']}%")
                : $query;
        }

        $query = $sort_by != 'name'
            ? $query->orderBy("brands.$sort_by", $order_by)
            : $query->orderBy('brand_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $query->select('brands.*', 'brand_language.name', 'brand_language.slug');

        return $query->distinct('brands.id')->paginate($per_page);
    }

    public function get($value, $conditions = [], $column = 'slug', $with = [])
    {
        $id = (integer) $value;

        if (!$id) {
            $this->brand_language_model = $this->brand_language_model->where($column, $value)->firstOrFail();
            $id =  $this->brand_language_model->brand_id;
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where('id', $id)->firstOrFail();
        return $data;
    }
}
