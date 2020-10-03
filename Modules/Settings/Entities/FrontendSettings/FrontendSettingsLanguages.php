<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FrontendSettingsLanguages extends Pivot
{
    protected $table = 'frontend_settings_languages';
    protected $fillable = [
        'setting_id', 'language_id',
        'home_page_title','home_page_meta_desc'
    ];
}
