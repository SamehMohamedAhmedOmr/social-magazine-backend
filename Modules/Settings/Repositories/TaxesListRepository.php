<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\TaxesList;

class TaxesListRepository extends LaravelRepositoryClass
{
    public function __construct(TaxesList $taxes_list)
    {
        $this->model = $taxes_list;
        $this->cache_key = 'taxes_lists';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){
        $query = $this->model;

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $where_conditions = ($search_keys) ? [
                    ['taxes_list_languages.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language',
                    $search_keys, $where_conditions, $or_where_conditions);


                $q = $q->orWhereHas('taxType', function ($client_query) use ($search_keys) {
                    if ($search_keys) {
                        $client_query->where('name', 'LIKE', '%'.$search_keys.'%');
                    }
                });

                $q = $q->orWhereHas('amountType', function ($client_query) use ($search_keys) {
                    if ($search_keys) {
                        $client_query->where('name', 'LIKE', '%'.$search_keys.'%');
                    }
                });

                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('key', 'LIKE', '%'.$search_keys.'%');


            });
        }

        return $query;
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $with != []
            ? $data->with($with)
            : $data;

        $data = $column
            ? $data->where($column, $value)
            : $data;

        $data = $data->first();

        return $data;
    }

    public function syncLanguage($tax_list, $taxes_list_languages)
    {
        $tax_list->language()->sync($taxes_list_languages);
        return $tax_list;
    }

    public function updateLanguage($tax_list, $taxes_list_languages)
    {
        $tax_list->language()->detach();
        $tax_list->language()->attach($taxes_list_languages);
        return $tax_list;
    }
}
