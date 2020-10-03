<?php

namespace Modules\Base\Helpers;

use \Modules\Settings\Repositories\LanguageRepository;

class LanguageHelper
{
    /**
     * @var object
     */
    private $language_repository;

    /**
     * Init new object.
     *
     * @param   LanguageRepository  $language_repository
     */
    public function __construct(LanguageRepository $language_repository)
    {
        $this->language_repository = $language_repository;
    }

    public function loadLanguage($query, $lang, $relation_name, $search_keys, $where_conditions, $or_where_conditions)
    {
        $query = $query->with([$relation_name => function ($language_query) use ($lang) {
            $language_query->where('language_id', $lang);
        }]);

        $query = $query->whereHas($relation_name, function ($language_query) use ($lang, $search_keys ,$where_conditions, $or_where_conditions) {
            if ($search_keys) {
                $language_query->where($where_conditions)->orwhere($or_where_conditions);
            }
        });

        return $query;
    }

    public function loadCurrentLanguage($resource, $key = 'name'){
        $name = null;
        if ($resource->relationLoaded('currentLanguage')){
            $name = isset($resource->currentLanguage[$key]) ? $resource->currentLanguage[$key] : null;
        }
        return $name;
    }

    public function prepareLanguagesForResource($resource , $value ,$key){
        if ($value){
            $resource[$key] = $value;
        }
        return $resource;
    }

    /**
     * @return array iso for the active languages
     */
    public function getActiveISO()
    {
        return $this->language_repository->getActiveISO();
    }
}
