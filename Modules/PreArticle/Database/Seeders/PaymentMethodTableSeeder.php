<?php

namespace Modules\PreArticle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\PreArticle\Entities\PaymentMethod;
use Modules\PreArticle\Facades\PaymentMethodCollection;
use Modules\PreArticle\Facades\PreArticleCache;

class PaymentMethodTableSeeder extends Seeder
{
    public function payment_methods()
    {
        $payment_methods = collect([]);
        $payment_methods->push(PaymentMethodCollection::MANUAL_PAYMENT());

        return $payment_methods;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CacheHelper::forgetCache(PreArticleCache::paymentMethod());

        $payment_methods = $this->payment_methods();

        $this->seed($payment_methods);
    }

    protected function seed($payment_methods){
        foreach ($payment_methods as $payment_method) {

            PaymentMethod::updateOrCreate([
                'name' => $payment_method['name'],
                'key' => $payment_method['key'],
            ],[]);
        }
    }
}
