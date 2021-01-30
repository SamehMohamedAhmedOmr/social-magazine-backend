<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachmentType extends Model
{
    use SoftDeletes;

    protected $table = 'attachments_type';

    protected $fillable = [
        'name' , 'key'
    ];
}
