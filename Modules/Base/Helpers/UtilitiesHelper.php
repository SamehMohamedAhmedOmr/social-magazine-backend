<?php

namespace Modules\Base\Helpers;

use Request;

class UtilitiesHelper
{

    public function prepareDeviceHeader()
    {
        $device_id = trim(Request::header('device_id'));
        $device_os = trim(Request::header('device_os'));
        $app_version = trim(Request::header('app_version'));

        return [
            (string)$device_id,
            (string)$device_os,
            (string)$app_version
        ];
    }

    public function getCountryId()
    {
        if (!request()->has('country_id')) {
            $country_id = \Session::get('country_id');
        } else {
            $country_id = request('country_id');
        }
        return $country_id;
    }

    public function projectSlug(){
        $project_folder = \Session::get(\Session::get('current_sub_domain'));
        return isset($project_folder) ? $project_folder['slug'] : '8000';
    }
}
