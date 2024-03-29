<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'payment_method';

    protected $fillable = [
        'name' , 'key'
    ];
}
