<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Basic\Entities\Country;
use Modules\Basic\Entities\EducationalDegree;
use Modules\Basic\Entities\EducationalLevel;
use Modules\Basic\Entities\Gender;
use Modules\Basic\Entities\Title;

class ArticleAuthors extends Model
{
    use SoftDeletes;
    protected $table = 'article_authors';

    protected $fillable = [
        'first_name', 'family_name', 'email', 'alternative_email',
        'gender_id', 'title_id', 'educational_level_id', 'educational_degree_id',
        'phone_number', 'address', 'country_id', 'article_id'
    ];

    protected $with = [
        'gender',
        'title',
        'educationalLevel',
        'educationalDegree',
        'country',
    ];

    public function title(){
        return $this->belongsTo(Title::class,'title_id','id');
    }

    public function educationalLevel(){
        return $this->belongsTo(EducationalLevel::class,'educational_level_id','id');
    }

    public function educationalDegree(){
        return $this->belongsTo(EducationalDegree::class,'educational_degree_id','id');
    }

    public function gender(){
        return $this->belongsTo(Gender::class,'gender_id','id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
