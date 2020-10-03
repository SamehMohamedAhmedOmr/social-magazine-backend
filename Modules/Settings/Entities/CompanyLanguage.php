<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyLanguage extends Pivot
{
    protected $fillable = ['language_id','company_id','name','description'];
}
