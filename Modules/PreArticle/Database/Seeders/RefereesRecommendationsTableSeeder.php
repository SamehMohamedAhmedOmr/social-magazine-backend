<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\RefereesRecommendations;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\StatusFilterCollection;

class ArticleFilterTableSeeder extends Seeder
{
    public function recommendations()
    {
        $recommendations = collect([]);
        $recommendations->push(StatusFilterCollection::NEW());


        return $recommendations;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::refereesRecommendation());

        $recommendations = $this->recommendations();

        $this->seed($recommendations);
    }

    protected function seed($recommendations){
        foreach ($recommendations as $recommendation) {

            RefereesRecommendations::updateOrCreate([
                'name' => $recommendation['name'],
                'key' => $recommendation['key'],
            ],[]);
        }
    }
}
