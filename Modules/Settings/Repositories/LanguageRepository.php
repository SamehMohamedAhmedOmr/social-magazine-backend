<?php

namespace Modules\Settings\Repositories;

use Illuminate\Support\Facades\Cache;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Language;

class LanguageRepository extends LaravelRepositoryClass
{
    public function __construct(Language $language_model)
    {
        $this->model = $language_model;
        $this->cache_key = 'languages';
    }

    public function getActiveISO()
    {
        return $this->model->where('is_active', 1)->pluck('iso')->toArray();
    }

    public function getAllISO()
    {
        return $this->model->pluck('iso')->toArray();
    }

    public function pluckISOId()
    {
        return $this->model->where('is_active', 1)->pluck('id', 'iso')->toArray();
    }

    public function getLang()
    {
        // ToDo  get language from cache to enhance performance
        return $this->model->where('is_active', 1)->get();
    }

    public function getLangId($iso)
    {
        $iso = $iso == null ? 'en' : $iso;
        if (Cache::has($this->cache_key."_$iso")) {
            return Cache::get($this->cache_key."_$iso")->id;
        }
        return $this->get($iso, [], 'iso')->id;
    }
}
