<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\ACL\Entities\Role;
use Modules\ACL\Entities\UserHasRoles;
use Modules\Basic\Entities\Country;
use Modules\Basic\Entities\EducationalDegree;
use Modules\Basic\Entities\EducationalLevel;
use Modules\Basic\Entities\Gender;
use Modules\Basic\Entities\Title;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'family_name' , 'email', 'email_verified_at' ,'password',
        'alternative_email' , 'token_last_renew', 'is_active',
        'title_id' , 'educational_level_id', 'educational_degree_id', 'gender_id',
        'educational_field' , 'university', 'faculty', 'phone_number',
        'fax_number', 'address', 'country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accountTypes(){
        return $this->belongsToMany(UserTypes::class,'account_types','user_id','user_type_id')
            ->using(AccountType::class);
    }

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

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id'
        )
            ->using(UserHasRoles::class);
    }


}
