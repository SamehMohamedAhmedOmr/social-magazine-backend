<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\ArticleFilter;
use Modules\PreArticle\Entities\CurrencyType;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\StatusFilterCollection;

class ArticleFilterTableSeeder extends Seeder
{
    public function types()
    {
        $types = collect([]);
        $types->push(StatusFilterCollection::NEW());


        return $types;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::currencyType());

        $types = $this->types();

        $this->seed($types);
    }

    protected function seed($types){
        foreach ($types as $type){

            CurrencyType::updateOrCreate([
                'name' => $type['name'],
                'key' => $type['key'],
            ],[]);
        }
    }
}
