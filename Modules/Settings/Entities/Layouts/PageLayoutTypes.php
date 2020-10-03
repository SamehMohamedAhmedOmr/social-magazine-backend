<?php

namespace Modules\Settings\Entities\Layouts;

use Illuminate\Database\Eloquent\Model;

class PageLayoutTypes extends Model
{

    protected $table = 'pages_layout_types';
    protected $fillable = [
        'name' , 'key'
    ];

    public function PagesLayout(){
        return $this->hasMany(PageLayout::class, 'layout_type_id','id');
    }

}
