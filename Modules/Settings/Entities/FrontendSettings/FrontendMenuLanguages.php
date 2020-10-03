<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FrontendMenuLanguages extends Pivot
{
    protected $table = 'frontend_menu_languages';
    protected $fillable = [
        'menu_id', 'language_id', 'name'
    ];
}
