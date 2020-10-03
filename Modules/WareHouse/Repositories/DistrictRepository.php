<?php


namespace Modules\WareHouse\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\District;

class DistrictRepository extends LaravelRepositoryClass
{
    protected $lang_model;
    public function __construct(District $district)
    {
        $this->model = $district;
        $this->cache = 'district';
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

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $where_conditions = ($search_keys) ? [
                    ['district_languages.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language', $search_keys,
                    $where_conditions, $or_where_conditions);


                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

    // add languages or update languages from object
    public function asyncObjectLanguages($data, $model)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->detach();
        $model->language()->attach($languagesObjects);
    }

    /* Front End Methods */
    public function getDistrictFront($lang = null, $sort_key = 'order', $sort_order = 'desc', $country_id)
    {
        $query = $this->model->where('is_active', 1)->where('country_id', $country_id);
        // get related data to model
        return $query->with(['language' => function ($query) use ($lang) {
            $query->where('language_id', $lang);
        }])->orderBy($sort_key, $sort_order)->get();
    }

    public function getDistrict($id, $with = true)
    {
        if ($with) {
            $district = $this->model->with('warehouse.products')->find($id);
        } else {
            $district = $this->model->find($id);
        }
        return $district;
    }
}
