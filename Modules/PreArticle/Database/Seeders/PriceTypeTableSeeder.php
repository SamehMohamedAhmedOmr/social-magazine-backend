<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\PriceType;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\PriceTypeCollection;

class PriceTypeTableSeeder extends Seeder
{
    public function types()
    {
        $types = collect([]);
        $types->push(PriceTypeCollection::ARTICLE_JUDGEMENT_FEES());
        $types->push(PriceTypeCollection::ARTICLE_ACCEPTANCE_FEES());


        return $types;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::priceType());

        $types = $this->types();

        $this->seed($types);
    }

    protected function seed($types){
        foreach ($types as $type){

            PriceType::updateOrCreate([
                'name' => $type['name'],
                'key' => $type['key'],
            ],[]);
        }
    }
}
