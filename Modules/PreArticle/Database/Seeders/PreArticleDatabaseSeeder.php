<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PreArticleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ArticleStatusTableSeeder::class);

        $this->call(ArticleFilterTableSeeder::class);

        $this->call(ArticleSubjectTableSeeder::class);
        $this->call(ArticleTypeTableSeeder::class);
        $this->call(AttachmentTypeTableSeeder::class);
        $this->call(CurrencyTypeTableSeeder::class);
        $this->call(PaymentMethodTableSeeder::class);
        $this->call(PriceTypeTableSeeder::class);
        $this->call(RefereesRecommendationsTableSeeder::class);

    }
}
