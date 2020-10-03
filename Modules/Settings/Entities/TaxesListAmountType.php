<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;

class TaxesListAmountType extends Model
{
    protected $table = 'taxes_amount_types';
    protected $fillable = ['name','key'];
}
