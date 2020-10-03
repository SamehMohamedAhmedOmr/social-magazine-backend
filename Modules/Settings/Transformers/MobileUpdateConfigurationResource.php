<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class MobileUpdateConfigurationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_update_request' => $this['is_update_request'],
            'force_update' => $this['force_update']
        ];
    }
}
