<?php

namespace Modules\FacebookCatalogue\Entities;

use Illuminate\Database\Eloquent\Model;

class FacebookCatalogueLog extends Model
{
    protected $table = 'facebook_catalogue_logs';
    protected $fillable = ['file_name', 'file_url', 'export_type'];
    protected $hidden = ['updated_at'];
}
