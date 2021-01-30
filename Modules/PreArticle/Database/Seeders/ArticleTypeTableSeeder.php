<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\ArticleFilter;
use Modules\PreArticle\Entities\ArticleType;
use Modules\PreArticle\Facades\ArticleTypeCollection;
use Modules\PreArticle\Facades\PreArticleCache;

class ArticleTypeTableSeeder extends Seeder
{
    public function types()
    {
        $types = collect([]);
        $types->push(ArticleTypeCollection::ORIGINAL_ARTICLE());
        return $types;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::articleType());

        $types = $this->types();

        $this->seed($types);
    }

    protected function seed($types){
        foreach ($types as $type){

            ArticleType::updateOrCreate([
                'name' => $type['name'],
                'key' => $type['key'],
            ],[]);
        }
    }
}
