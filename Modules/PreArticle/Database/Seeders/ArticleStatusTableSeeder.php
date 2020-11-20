<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\ArticleStatusList;
use Modules\PreArticle\Entities\ArticleStatusType;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Facades\StatusListCollection;

class ArticleStatusTableSeeder extends Seeder
{

    public function statusList()
    {
        $status_list = collect([]);
        $status_list->push(StatusListCollection::newStatus());
        $status_list->push(StatusListCollection::specializedStatus());
        $status_list->push(StatusListCollection::reviewStatus());
        $status_list->push(StatusListCollection::rejectedStatus());
        $status_list->push(StatusListCollection::acceptedStatus());
        $status_list->push(StatusListCollection::withdrawalStatus());
        return $status_list;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::statusList());

        $statusList = $this->statusList();

        $article_status_type = ArticleStatusType::with('statusList')->get();

        $this->seed($article_status_type, $statusList);
    }

    protected function seed($article_status_type, $statusList)
    {
        foreach ($statusList as $status) {
            $target_status = $article_status_type->where('key', $status['type'])->first();
            if ($target_status) {
                $this->update($target_status, $status);

            } else { // insert for first time
                $this->insert($status);
            }
        }
    }

    protected function update($target_status, $status){
        foreach ($status['attributes'] as $attribute) {

            ArticleStatusList::updateOrCreate([
                'type_id' => $target_status->id,
                'name' => $attribute['name'],
                'key' => $attribute['key'],
                'description' => $attribute['description'],
            ],[]);
        }
    }


    protected function insert($status)
    {
        try {
            \DB::transaction(function () use ($status) {
                $name = ucwords(str_replace('_', ' ', $status['type']));

                $target_status = ArticleStatusType::create([
                    'name' => $name,
                    'key' => $status['type'],
                ]);

                $attributes = [];

                foreach ($status['attributes'] as $attribute) {

                    $attributes [] = new ArticleStatusList([
                        'type_id' => $target_status->id,
                        'name' => $attribute['name'],
                        'key' => $attribute['key'],
                        'description' => $attribute['description'],
                    ]);
                }

                $target_status->statusList()->saveMany($attributes);
            });
        } catch (\Throwable $e) {
        }
    }


}
