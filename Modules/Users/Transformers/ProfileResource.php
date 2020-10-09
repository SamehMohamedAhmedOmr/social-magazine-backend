<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ProfileResource extends Resource
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
            'first_name' => $this->first_name,
            'family_name' => $this->family_name,

            'email' => $this->email,
            'alternative_email' => $this->alternative_email,
            'is_active' => $this->is_active,

            'gender_id' => $this->gender_id, // TODO
            'title_id' => $this->title_id, // TODO
            'educational_level_id' => $this->educational_level_id, // TODO
            'educational_degree_id' => $this->educational_degree_id, // TODO
            'country_id' => $this->country_id, // TODO
            'types' => $this->type, // TODO

            'educational_field' => $this->educational_field,
            'university' => $this->university,
            'faculty' => $this->faculty,
            'phone_number' => $this->phone_number,
            'fax_number' => $this->fax_number,
            'address' => $this->address,
        ];
    }
}
