<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;

class TaxesListType extends Model
{
    protected $table = 'taxes_types';
    protected $fillable = ['name','key'];
}
