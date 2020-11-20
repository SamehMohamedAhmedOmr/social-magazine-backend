<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\ArticleFilter;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\StatusFilterCollection;

class ArticleFilterTableSeeder extends Seeder
{
    public function statusFilter()
    {
        $status_filter = collect([]);
        $status_filter->push(StatusFilterCollection::NEW());
        $status_filter->push(StatusFilterCollection::NOT_COMPLETED());
        $status_filter->push(StatusFilterCollection::SPECIALIZED_FOR_EDITOR());
        $status_filter->push(StatusFilterCollection::DONE_BY_EDITOR());
        $status_filter->push(StatusFilterCollection::SPECIALIZED_FOR_REFEREES());
        $status_filter->push(StatusFilterCollection::NOT_BEEN_JUDGED_AT_TIME());
        $status_filter->push(StatusFilterCollection::BEEN_JUDGED_FROM_ALL());
        $status_filter->push(StatusFilterCollection::BEEN_JUDGED_FROM_SOME());
        $status_filter->push(StatusFilterCollection::NEED_REVIEW());
        $status_filter->push(StatusFilterCollection::BEEN_REVIEWED());
        $status_filter->push(StatusFilterCollection::NOT_REVIEWED_AT_TIME());
        $status_filter->push(StatusFilterCollection::NOT_PUBLISHED());
        $status_filter->push(StatusFilterCollection::FINALLY_ACCEPTED());
        $status_filter->push(StatusFilterCollection::REJECTED());
        $status_filter->push(StatusFilterCollection::SENT_FOR_PAYMENT());

        return $status_filter;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::statusFilter());

        $statusFilter = $this->statusFilter();

        $this->seed($statusFilter);
    }

    protected function seed($filters){
        foreach ($filters as $filter) {

            ArticleFilter::updateOrCreate([
                'name' => $filter['name'],
                'key' => $filter['key'],
            ],[]);
        }
    }
}
