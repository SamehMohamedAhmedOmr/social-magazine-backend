<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\ArticleSubject;
use Modules\PreArticle\Facades\ArticleSubjectCollection;
use Modules\PreArticle\Facades\PreArticleCache;

class ArticleSubjectTableSeeder extends Seeder
{
    public function subjects()
    {
        $subjects = collect([]);
        $subjects->push(ArticleSubjectCollection::ARCHAEOLOGY());
        $subjects->push(ArticleSubjectCollection::SOCIOLOGY());
        $subjects->push(ArticleSubjectCollection::LITERATURE());
        $subjects->push(ArticleSubjectCollection::TOURIST_GUIDES());
        $subjects->push(ArticleSubjectCollection::MEDIA());
        $subjects->push(ArticleSubjectCollection::HISTORY());
        $subjects->push(ArticleSubjectCollection::GEOGRAPHY());
        $subjects->push(ArticleSubjectCollection::PHILOSOPHICAL_STUDIES());
        $subjects->push(ArticleSubjectCollection::ARTS());
        $subjects->push(ArticleSubjectCollection::LANGUAGES());
        $subjects->push(ArticleSubjectCollection::INFORMATION());
        $subjects->push(ArticleSubjectCollection::LIBRARIES());
        $subjects->push(ArticleSubjectCollection::PSYCHOLOGY());

        return $subjects;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::articleSubject());

        $subjects = $this->subjects();

        $this->seed($subjects);
    }

    protected function seed($subjects){
        foreach ($subjects as $subject) {

            ArticleSubject::updateOrCreate([
                'name' => $subject['name'],
                'key' => $subject['key'],
            ],[]);
        }
    }
}
