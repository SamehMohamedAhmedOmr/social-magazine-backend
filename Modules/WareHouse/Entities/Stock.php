<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Product;
use Modules\Settings\Entities\Company;
use Modules\Users\Entities\User;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'product_id', 'stock_quantity', 'from_warehouse' , 'to_warehouse',
        'type', 'is_active', 'purchase_order_id', 'company_id','country_id', "file_name", "user_id"
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CountryScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
