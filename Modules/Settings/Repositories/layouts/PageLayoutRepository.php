<?php

namespace Modules\Settings\Repositories\layouts;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Layouts\PageLayout;


class PageLayoutRepository extends LaravelRepositoryClass
{
    public function __construct(PageLayout $pageLayout)
    {
        $this->model = $pageLayout;
        $this->cache_key = 'page_layout';
    }

    public function getFirstRecord()
    {
        return $this->model->first();
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

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

    public function syncLanguage($frontend_settings, $languages)
    {
        $frontend_settings->languages()->sync($languages);
        return $frontend_settings;
    }

    public function updateLanguage($frontend_settings, $languages)
    {
        $frontend_settings->languages()->detach();
        $frontend_settings->languages()->attach($languages);
        return $frontend_settings;
    }

    public function relationships($additional = []){
        $base = [
            'colors' ,
            'socialMedia',
            'faviconImg' ,
            'socialSharingImg',
            'logoImg',
            'typography.mainFont',
            'typography.boldFont',
            'typography.regularFont',
            'typography.italicFont',
            'menu.navigationMenu'
        ];
        return array_merge($base, $additional);
    }


}
