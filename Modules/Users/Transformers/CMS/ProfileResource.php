<?php

namespace Modules\Users\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Users\Transformers\AccountTypeResource;

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

            'gender_id' => $this->gender_id,
            'title_id' => $this->title_id,
            'educational_level_id' => $this->educational_level_id,
            'educational_degree_id' => $this->educational_degree_id,
            'country_id' => $this->country_id,
            'types' => AccountTypeResource::collection($this->whenLoaded('accountTypes')),

            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ];
    }
}
