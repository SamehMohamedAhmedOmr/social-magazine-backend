<?php


namespace Modules\FrontendUtilities\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FrontendUtilities\Entities\Collection;

class CollectionRepository extends LaravelRepositoryClass
{
    protected $lang_model;
    public function __construct(Collection $collection_model)
    {
        $this->model = $collection_model;
        $this->cache = 'collection';
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

        $where_conditions = ($search_keys) ? [
            ['collection_languages.title', 'LIKE', '%'.$search_keys.'%']
        ] : [];

        $or_where_conditions = [];

        $query = LanguageFacade::loadLanguage($query, \Session::get('language_id'),
            'language', $search_keys, $where_conditions, $or_where_conditions);

        return $query;
    }

    public function asyncObjectLanguages($model, $data)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->sync($languagesObjects);
    }

    // add languages or update languages from object
    public function updateObjectLanguages($model, $data)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->detach();
        $model->language()->attach($languagesObjects);
    }


    public function attachProducts($model, $products)
    {
        $model->products()->attach($products);
    }

    // add languages or update languages from object
    public function updateProducts($model, $products)
    {
        $model->products()->detach();
        $model->products()->attach($products);
    }



    /* FRONT Methods*/
    public function getCollectionFront($lang = null, $sort_key = 'order', $sort_order = 'desc')
    {
        $query = $this->model->where('is_active', '1');
        // get related data to model
        return $query->with(['language' => function ($query) use ($lang) {
            $query->where('language_id', $lang);
        }])->orderBy($sort_key, $sort_order)->get();
    }
}
