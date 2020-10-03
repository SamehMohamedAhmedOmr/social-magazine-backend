<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Settings\Entities\Days;
use Modules\Settings\Entities\DaysLanguages;

class SeedDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days_en = ['Saturday' , 'Sunday' , 'Monday' , 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' ];
        $days_ar = ['السبت' , 'الأحد' , 'الأثنين' , 'الثلاثاء' , 'الاربعاء' , 'الخميس'  , 'الجمعه' ];
        $active_iso = getAllActiveLanguages();
        foreach ($active_iso as $lang_iso) {
            if ($lang_iso->iso == 'ar' || $lang_iso->iso == 'en') {
                for ($index = 0; $index < 7; $index++) {
                    $day = Days::updateOrCreate(['id' => ($index+1)]);
                    DB::table('days_languages')->updateOrInsert([
                        'day_id' => $day->id,
                        'name' => (($lang_iso->iso == 'ar') ? $days_ar[$index] : $days_en[$index]),
                        'language_id' => $lang_iso->id,
                    ]);
                }
            }
        }
    }
}
