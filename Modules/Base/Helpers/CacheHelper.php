<?php

namespace Modules\Base\Helpers;


class CacheHelper
{

    public function forgetCache($key){
        \Cache::forget($key);
    }

    public function getCache($key){
        return \Cache::get($key);
    }

    public function putCache($key, $value){
        \Cache::put($key, $value, now()->addDays(60));
    }

}
