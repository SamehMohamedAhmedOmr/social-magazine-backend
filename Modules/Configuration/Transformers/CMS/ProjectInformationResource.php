<?php

namespace Modules\Configuration\Transformers\CMS;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ProjectInformationResource extends Resource
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
            'image' => $this->image,
            'message' => $this->message,
            'active' => $this->active,
            'name' => $this->name,
            'slug' => $this->slug,
            'success' => $this->success,
        ];
    }
}
