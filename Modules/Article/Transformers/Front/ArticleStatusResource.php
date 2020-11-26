<?php

namespace Modules\Article\Transformers\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\PreArticle\Transformers\ArticleStatus;


class ArticleStatusResource extends Resource
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
            'status' => ArticleStatus::make($this->whenLoaded('status')),
        ];
    }
}
