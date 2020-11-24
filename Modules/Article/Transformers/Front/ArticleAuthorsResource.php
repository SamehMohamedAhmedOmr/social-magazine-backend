<?php

namespace Modules\Article\Transformers\Front;

use Illuminate\Http\Resources\Json\Resource;

class ArticleAuthorsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
