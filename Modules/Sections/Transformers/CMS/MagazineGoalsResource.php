<?php

namespace Modules\Sections\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class MagazineGoalsResource extends Resource
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
            'content' => $this->content,
            'is_active' => $this->is_active,
        ];
    }
}
