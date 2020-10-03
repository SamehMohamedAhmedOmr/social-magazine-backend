<?php

namespace Modules\Settings\Repositories;

use Illuminate\Support\Facades\Cache;
use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Company;

class CompanyRepository extends LaravelRepositoryClass
{
    public function __construct(Company $company)
    {
        $this->model = $company;
        $this->cache_key = 'company';
    }

    public function paginate($per_page = 15, $conditions = null, $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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
                    ['company_language.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = ($search_keys) ? [
                    [ 'company_language.description', 'LIKE', '%' . $search_keys . '%']
                ] : [];


                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language', $search_keys,
                    $where_conditions, $or_where_conditions);


                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

    public function updateImage($object, $value)
    {
        $object->logo = $value;
        $object->save();
        return $object;
    }

    public function syncLanguage($company, $company_languages)
    {
        $company->language()->sync($company_languages);
        return $company;
    }

    public function updateLanguage($company, $company_languages)
    {
        $company->language()->detach();
        $company->language()->attach($company_languages);
        return $company;
    }
}
