<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class MobileUpdateResource extends Resource
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
            'id' => $this->id,
            'device_type' => $this->device_type,
            'application_version' => $this->application_version,
            'build_number' => $this->build_number,
            'is_active' => isset($this->is_active) ? $this->is_active : 1,
            'force_update' => isset($this->force_update) ? $this->force_update : 0,
            'release_date' => $this->release_date
        ];
    }
}
