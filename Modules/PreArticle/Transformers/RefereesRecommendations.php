<?php

namespace Modules\PreArticle\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class RefereesRecommendations extends Resource
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
            'name' => $this->name,
            'key' => $this->key,
        ];
    }
}
