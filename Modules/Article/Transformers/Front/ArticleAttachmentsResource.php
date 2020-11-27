<?php

namespace Modules\Article\Transformers\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\PreArticle\Transformers\AttachmentType;
use Modules\Users\Transformers\UserResource;

class ArticleAttachmentsResource extends Resource
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
            'title' => $this->title,
            'description' => $this->description,
            'upload_by' => UserResource::make($this->whenLoaded('uploadBy')),
            'attachment_type' => AttachmentType::make($this->whenLoaded('attachmentType')),
            'file' => $this->getFile($this->article_id, $this->file),
            'article_id' => $this->article_id,
            'status_id ' => $this->status_id ,
        ];
    }

    private function getFile($article_id, $file)
    {
        $path = 'public/files/'. $article_id .'/'. $file ;
        return url(\Storage::url($path));
    }
}
