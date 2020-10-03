<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendSocialMedia extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_social_media';

    protected $fillable = [
        'facebook' , 'twitter', 'instagram',
        'youtube' , 'google_plus', 'pinterest' , 'linkedin', 'frontend_setting_id'
    ];

    public function FrontendSettings(){
        return $this->belongsTo(FrontendSettings::class,'id','frontend_setting_id');
    }

}
