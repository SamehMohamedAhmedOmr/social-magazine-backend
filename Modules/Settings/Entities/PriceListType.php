<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceListType extends Model
{
    use SoftDeletes;
    protected $table = 'price_list_types';
    protected $fillable = ['name'];
}
