<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountType extends Pivot
{
    protected $table = 'account_types';
    protected $fillable = [
        'user_id', 'user_type_id'
    ];

}
