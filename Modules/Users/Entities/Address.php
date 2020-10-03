<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\WareHouse\Entities\Country;
use Modules\WareHouse\Entities\District;
use Modules\WareHouse\Entities\Order\Order;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'address';
    protected $fillable = [
        'title','user_id',
        'district_id','city_id','country_id',
        'street','nearest_landmark',
        'address_phone','building_no','floor_no','apartment_no',
        'lat','lng','is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }
}
