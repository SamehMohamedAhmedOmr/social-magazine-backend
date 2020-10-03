<?php

namespace Modules\Catalogue\Transformers\CMS\UnitOfMeasure;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

class UnitOfMeasureResource extends Resource
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
            'name' => LanguageFacade::loadCurrentLanguage($this),
            'is_active' => (boolean)$this->is_active,
            'languages' => UnitOfMeasureNamesResource::collection($this->languages),
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
