<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class TaxesListLanguagesResource extends Resource
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
            'lang' => $this->iso,
            'name' => $this->pivot->name,
        ];
    }
}
