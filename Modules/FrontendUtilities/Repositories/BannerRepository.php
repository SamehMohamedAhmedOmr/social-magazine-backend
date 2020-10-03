<?php


namespace Modules\FrontendUtilities\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FrontendUtilities\Entities\Banner;

class BannerRepository extends LaravelRepositoryClass
{
    protected $lang_model;
    public function __construct(Banner $banner_model)
    {
        $this->model = $banner_model;
        $this->cache = 'banner';
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
            ['title', 'LIKE', '%'.$search_keys.'%']
        ] : [];

        $or_where_conditions = ($search_keys) ? [
            [ 'description', 'LIKE', '%' . $search_keys . '%']
        ] : [];

        $query = LanguageFacade::loadLanguage($query, \Session::get('language_id'),
            'language', $search_keys, $where_conditions, $or_where_conditions);


        return $query;
    }

    // add languages or update languages from object
    public function asyncObjectLanguages($data, $model)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->sync($languagesObjects);
    }

    // add languages or update languages from object
    public function updateObjectLanguages($data, $model)
    {
        $languagesObjects = prepareObjectLanguages($data);
        $model->language()->detach();
        $model->language()->attach($languagesObjects);
    }


    public function restore($id)
    {
        $trashed_banner = $this->model->withTrashed()->find($id);
        $trashed_banner->restore();
    }

    /* Front End Methods */
    public function getBannerFront($lang = null, $sort_key = 'order', $sort_order = 'desc')
    {
        $query = $this->model->where('is_active', '1');

        return $query->get();
    }
}
