<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Gallery\Entities\Gallery;
use Modules\Settings\Entities\Fonts;

class FrontendTypography extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_typography';

    protected $fillable = [
        'main_font', 'bold_font',
        'regular_font' , 'italic_font',
        'frontend_setting_id'
    ];

    public function FrontendSettings(){
        return $this->belongsTo(FrontendSettings::class,'id','frontend_setting_id');
    }

    public function mainFont()
    {
        return $this->hasOne(Fonts::class, 'id','main_font');
    }

    public function boldFont()
    {
        return $this->hasOne(Fonts::class, 'id','bold_font');
    }

    public function regularFont()
    {
        return $this->hasOne(Fonts::class, 'id','regular_font');
    }

    public function italicFont()
    {
        return $this->hasOne(Fonts::class, 'id','italic_font');
    }


}
