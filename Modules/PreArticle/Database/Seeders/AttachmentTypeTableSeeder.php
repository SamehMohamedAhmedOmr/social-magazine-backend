<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\AttachmentType;
use Modules\PreArticle\Facades\AttachmentTypeCollection;
use Modules\PreArticle\Facades\PreArticleCache;

class AttachmentTypeTableSeeder extends Seeder
{
    public function types()
    {
        $types = collect([]);
        $types->push(AttachmentTypeCollection::COVER_PAGE());
        $types->push(AttachmentTypeCollection::ORIGINAL_ARTICLE());
        $types->push(AttachmentTypeCollection::SUMMARY_OF_RESEARCH());

        return $types;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::attachmentsType());

        $types = $this->types();

        $this->seed($types);
    }

    protected function seed($types){
        foreach ($types as $type){

            AttachmentType::updateOrCreate([
                'name' => $type['name'],
                'key' => $type['key'],
            ],[]);
        }
    }
}
