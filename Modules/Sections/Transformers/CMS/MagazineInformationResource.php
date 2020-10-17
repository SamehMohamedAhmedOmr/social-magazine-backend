<?php

namespace Modules\Sections\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class MagazineInformationResource extends Resource
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
            'vision' => $this->vision,
            'mission' => $this->mission,
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'fax_number' => $this->fax_number,
            'email' => $this->email,
            'postal_code' => $this->postal_code,
            'visitor_number' => $this->visitor_number,
        ];
    }
}
