<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentEntryLogs extends Model
{
    protected $table = 'purchase_payment_entry_logs';

    protected $fillable = ['purchase_payment_entry','user_id','log_type','payment_entry_id'];
}
