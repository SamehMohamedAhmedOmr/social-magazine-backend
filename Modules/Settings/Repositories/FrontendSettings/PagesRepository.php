<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Pages;

class PagesRepository extends LaravelRepositoryClass
{
    public function __construct(Pages $pages)
    {
        $this->model = $pages;
        $this->cache_key = 'pages';
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
                    ['pages_languages.title', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = ($search_keys) ? [
                    [ 'pages_languages.seo_title', 'LIKE', '%' . $search_keys . '%']
                ] : [];


                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'languages',
                    $search_keys, $where_conditions, $or_where_conditions);


                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

    public function syncLanguage($object, $languages)
    {
        $object->languages()->sync($languages);
        return $object;
    }

    public function updateLanguage($object, $languages)
    {
        $object->languages()->detach();
        $object->languages()->attach($languages);
        return $object;
    }

}
