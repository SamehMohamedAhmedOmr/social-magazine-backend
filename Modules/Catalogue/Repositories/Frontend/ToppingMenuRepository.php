<?php

namespace Modules\Catalogue\Repositories\Frontend;

use Illuminate\Support\Facades\Cache;
use Modules\Catalogue\Jobs\AddToCache;
use Modules\Catalogue\Repositories\Common\ToppingMenuCommonRepository;

class ToppingMenuRepository extends ToppingMenuCommonRepository
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
        $query = $this->model->join('topping_menu_language', 'topping_menus.id', 'topping_menu_language.topping_menu_id')
            ->where('topping_menus.is_active', true)
            ->where('topping_menu_language.language_id', $filter_language);

        $query = $search['search_key'] !== null
            ? $query->where('topping_menu_language.name', 'LIKE', "%{$search['search_key']}%")
            : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy("topping_menus.$sort_by", $order_by)
            : $query->orderBy('topping_menu_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $query->select('topping_menus.*', 'topping_menu_language.name', 'topping_menu_language.slug');

        return $query->distinct('topping_menus.id')->paginate($per_page);
    }

    public function get($value, $conditions = [], $column = 'slug', $with = [])
    {
        $id = (integer) $value;

        if (!$id) {
            $this->topping_language_model = $this->topping_language_model->where($column, $value)->firstOrFail();
            $id = $this->topping_language_model->topping_menu_id;
        }

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where('id', $id)->firstOrFail()
            ->load(['currentLanguage',
                'products' => function ($query) {
                    $query->whereHas('mainCategory', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->whereHas('brand', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->where('is_active', true)->where('is_topping', true)->where('deleted_at', null);
                }]);

        return $data;
    }
}
