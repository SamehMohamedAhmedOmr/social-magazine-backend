<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Notifications\Entities\CMSNotification;
use Modules\WareHouse\Entities\AdminCountries;
use Modules\WareHouse\Entities\AdminWarehouses;
use Modules\WareHouse\Entities\Country;
use Modules\WareHouse\Entities\Warehouse;

class Admin extends Model
{
    use SoftDeletes, Notifiable;

    protected $table = 'admin';
    protected $fillable = ['user_id'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(
            Warehouse::class,
            'admin_warehouses',
            'admin_id',
            'warehouse_id'
        )
            ->using(AdminWarehouses::class);
    }

    public function countries()
    {
        return $this->belongsToMany(
            Country::class,
            'admin_countries',
            'admin_id',
            'country_id'
        )
            ->using(AdminCountries::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'Modules.Users.Admin.'.$this->id;
    }
}
