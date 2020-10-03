<?php


namespace Modules\WareHouse\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Country;

class CountryRepository extends LaravelRepositoryClass
{
    protected $lang_model;
    protected $admin_type = 1;


    public function __construct(Country $country)
    {
        $this->model = $country;
        $this->cache = 'country';
    }


    /* CMS Methods */
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

        list($country_ids, $is_admin) = $this->countryIds();

        if ($is_admin) {
            $query = $query->whereIn('id', $country_ids);
        }

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){
                $where_conditions = ($search_keys) ? [
                    ['country_languages.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language', $search_keys,
                    $where_conditions, $or_where_conditions);

                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('country_code', 'LIKE', '%'.$search_keys.'%');
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

        list($country_ids, $is_admin) = $this->countryIds();

        if ($is_admin) {
            $data = $data->whereIn('id', $country_ids);
        }

        $data = $column
            ? $data->where($column, $value)
            : $data;

        $data = $data->first();

        return $data;
    }

    public function countryIds()
    {
        $country_ids = [];
        $is_admin = false;
        $user = \Auth::user();
        if ($user) {
            if ($user->user_type == $this->admin_type) {
                $user->load('admin.countries');
                $is_admin = true;
                $country_ids = $user->admin->countries->pluck('id')->toArray();
            }
        }
        return [$country_ids , $is_admin];
    }

    // add languages or update languages from object
    public function asyncObjectLanguages($data, $model)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->detach();
        $model->language()->attach($languagesObjects);
    }

    /* Front End Methods */
    public function getCountriesFront($lang = null, $sort_key = 'order', $sort_order = 'desc')
    {
        $query = $this->model->where('is_active', 1);
        // get related data to model
        return $query->with(['language' => function ($query) use ($lang) {
            $query->where('language_id', $lang);
        }])->orderBy($sort_key, $sort_order)->get();
    }
}
