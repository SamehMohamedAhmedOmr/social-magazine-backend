<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicationRules extends Model
{
    use SoftDeletes;
    protected $table = 'publication_rules';
    protected $fillable = [
        'content', 'is_active'
    ];
}
