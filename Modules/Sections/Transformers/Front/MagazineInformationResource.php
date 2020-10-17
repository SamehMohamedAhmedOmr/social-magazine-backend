<?php

namespace Modules\Sections\Transformers\Front;

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
            'title' => $this->title,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'address' => $this->address,
            'phone' => $this->phone,
            'fax_number' => $this->fax_number,
            'email' => $this->email,
            'postal_code' => $this->postal_code,
        ];
    }
}
