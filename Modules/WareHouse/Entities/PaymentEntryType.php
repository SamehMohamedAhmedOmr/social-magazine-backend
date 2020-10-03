<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentEntryType extends Model
{
    protected $table = 'payment_entry_types';
    protected $fillable = ['name','key'];
}
