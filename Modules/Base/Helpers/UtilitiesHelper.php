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

    public function generateSlug($string){
        if (is_null($string)) {
            return "";
        }

        // Remove spaces from the beginning and from the end of the string
        $string = trim($string);

        // Lower case everything
        // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
        $string = mb_strtolower($string, "UTF-8");;

        // Make alphanumeric (removes all other characters)
        // this makes the string safe especially when used as a part of a URL
        // this keeps latin characters and arabic charactrs as well
        $string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

        // Remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        // Convert whitespaces and underscore to the given separator
        $string = preg_replace("/[\s_]/", '-', $string);

        return $string;
    }
}
