<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendMenuNavigationType extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_menu_navigation_type';

    protected $fillable = [
        'name' , 'key'
    ];



}
