<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaymentMethodLanguages extends Pivot
{
    protected $table = 'payment_methods_languages';
    protected $fillable = ['language_id','name'];
}
