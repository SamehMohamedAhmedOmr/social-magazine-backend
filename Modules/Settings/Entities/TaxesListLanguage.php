<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaxesListLanguage extends Pivot
{
    protected $table = 'taxes_list_languages';
    protected $fillable = ['name','language_id'];
}
