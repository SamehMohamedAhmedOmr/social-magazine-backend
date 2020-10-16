<?php

namespace Modules\Users\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class UsersTypesHelper
 * @package Modules\Users\Helpers
 */
class UsersHelper
{

    public function prepareUpdateProfile($request_body){
        $body = [];
        $body = $this->returnProfileUpdateBody($body,$request_body ,'first_name');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'family_name');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'email');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'alternative_email');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'phone_number');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'country_id');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'gender_id');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'title_id');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'educational_level_id');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'educational_degree_id');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'educational_field');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'university');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'faculty');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'fax_number');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'address');
        return $body;
    }

    private function returnProfileUpdateBody($body, $request_body ,$key){
        if (isset($request_body[$key])){
            $body[$key] = $request_body[$key];
        }
        return $body;
    }


}
