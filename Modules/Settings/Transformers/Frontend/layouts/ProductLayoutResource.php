<?php

namespace Modules\Settings\Transformers\Frontend\layouts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ProductLayoutResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $list_type = $this->pagesLayout->where('key','list_type')->first();

        return [
            'list_type' => $list_type->value,
        ];
    }
}
