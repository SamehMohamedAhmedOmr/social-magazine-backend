<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\FrontendSettings\FrontendMenu;

class FrontendMenuRepository extends LaravelRepositoryClass
{
    public function __construct(FrontendMenu $frontend_menu)
    {
        $this->model = $frontend_menu;
        $this->cache_key = 'frontend_menu';
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
                    ['frontend_menu_languages.name', 'LIKE', '%'.$search_keys.'%']
                ] : [];

                $or_where_conditions = [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'languages', $search_keys,
                    $where_conditions, $or_where_conditions);


                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }


    public function syncLanguage($menu, $languages)
    {
        $menu->languages()->sync($languages);
        return $menu;
    }

    public function updateLanguage($menu, $languages)
    {
        $menu->languages()->detach();
        $menu->languages()->attach($languages);
        return $menu;
    }

}
