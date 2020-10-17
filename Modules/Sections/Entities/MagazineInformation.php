<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;

class MagazineInformation extends Model
{
    protected $table = 'magazine_information';
    protected $fillable = [
        'title', 'vision', 'mission', 'address',
        'phone' , 'fax_number', 'email', 'postal_code',
        'visitor_number'
    ];


}
