<?php

namespace Modules\Article\Transformers\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\PreArticle\Transformers\ArticleType;
use Modules\Users\Transformers\UserResource;

class ArticleResource extends Resource
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
            'slug' => $this->slug,
            'code' => $this->article_code,

            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,

            'content_ar' => $this->content_ar,
            'content_en' => $this->content_en,

            'keywords_en' => $this->keywords_en,
            'keywords_ar' => $this->keywords_ar,

            'article_type' => ArticleType::make($this->whenLoaded('type')),
            'main_author' => UserResource::make($this->whenLoaded('mainAuthor')),
            'last_status' => ArticleStatusResource::make($this->whenLoaded('lastStatus')),
        ];
    }
}
