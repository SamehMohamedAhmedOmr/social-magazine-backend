<?php

namespace Modules\Users\Transformers\FRONT;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Basic\Transformers\CountryResource;
use Modules\Basic\Transformers\EducationalDegreeResource;
use Modules\Basic\Transformers\EducationalLevelResource;
use Modules\Basic\Transformers\GenderResource;
use Modules\Basic\Transformers\TitleResource;
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

            'gender' => GenderResource::make($this->whenLoaded('gender')),
            'title' => TitleResource::make($this->whenLoaded('title')),
            'educational_level' => EducationalLevelResource::make($this->whenLoaded('educationalLevel')),
            'educational_degree' => EducationalDegreeResource::make($this->whenLoaded('educationalDegree')),
            'country' => CountryResource::make($this->whenLoaded('country')),

            'educational_field' => $this->educational_field,
            'university' => $this->university,
            'faculty' => $this->faculty,
            'phone_number' => $this->phone_number,
            'fax_number' => $this->fax_number,
            'address' => $this->address,
        ];
    }
}
