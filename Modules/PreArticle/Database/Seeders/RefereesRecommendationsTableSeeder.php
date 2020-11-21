<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\RefereesRecommendations;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\RefereesRecommendationsCollection;

class RefereesRecommendationsTableSeeder extends Seeder
{
    public function recommendations()
    {
        $recommendations = collect([]);
        $recommendations->push(RefereesRecommendationsCollection::ACCEPTED());
        $recommendations->push(RefereesRecommendationsCollection::PARTIAL_REVIEW());
        $recommendations->push(RefereesRecommendationsCollection::FULL_REVIEW());
        $recommendations->push(RefereesRecommendationsCollection::REJECTED());
        $recommendations->push(RefereesRecommendationsCollection::UNABLE_TO_JUDGEMENT());


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
