<?php

namespace Modules\FacebookCatalogue\Entities;

use Illuminate\Database\Eloquent\Model;

class FacebookCatalogueSettings extends Model
{
    protected $table = 'facebook_catalogue_settings';
    protected $fillable = ['domain_uri_prefix', 'android_package_name', 'android_fallback_link',
        'android_min_package_version_code', 'ios_bundle_id', 'ios_fallback_link'];
}
