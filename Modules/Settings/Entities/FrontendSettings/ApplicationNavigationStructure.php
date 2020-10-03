<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;

class ApplicationNavigationStructure extends Model
{
    protected $table = 'application_navigation_structure';
    protected $fillable = [
        'name' , 'key'
    ];

}
