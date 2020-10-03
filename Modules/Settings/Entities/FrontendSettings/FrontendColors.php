<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendColors extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_colors';

    protected $fillable = [
        'main_color' , 'second_color',
        'third_color', 'frontend_setting_id'
    ];

    public function FrontendSettings(){
        return $this->belongsTo(FrontendSettings::class,'id','frontend_setting_id');
    }

}
