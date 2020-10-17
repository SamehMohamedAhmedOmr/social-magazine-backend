<?php

namespace Modules\Sections\Helpers;


class SectionsHelper
{

    public function prepareUpdateMagazineInformation($request_body){
        $body = [];
        $body = $this->returnProfileUpdateBody($body,$request_body ,'vision');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'mission');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'address');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'phone');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'fax_number');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'email');
        $body = $this->returnProfileUpdateBody($body,$request_body ,'postal_code');
        return $body;
    }

    private function returnProfileUpdateBody($body, $request_body ,$key){
        if (isset($request_body[$key])){
            $body[$key] = $request_body[$key];
        }
        return $body;
    }


}
