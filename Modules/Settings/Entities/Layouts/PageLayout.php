<?php

namespace Modules\Settings\Entities\Layouts;

use Illuminate\Database\Eloquent\Model;

class PageLayout extends Model
{
    protected $table = 'pages_layout';

    protected $fillable = [
        'layout_type_id' , 'name', 'key', 'value'
    ];

}
