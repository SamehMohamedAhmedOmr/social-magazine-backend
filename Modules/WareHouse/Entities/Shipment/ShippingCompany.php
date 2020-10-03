<?php

namespace Modules\WareHouse\Entities\Shipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompany extends Model
{
    use SoftDeletes;
    protected $table = 'shipping_companies';
    protected $fillable = ['name', 'key', 'is_active'];
}
