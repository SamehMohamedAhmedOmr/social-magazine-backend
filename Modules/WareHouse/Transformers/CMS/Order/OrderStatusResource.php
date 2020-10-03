<?php

namespace Modules\WareHouse\Transformers\CMS\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Base\Facade\LanguageFacade;

class OrderStatusResource extends Resource
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
            'languages' => OrderStatusNamesResource::collection($this->languages),
            'key' => $this->key,
            'is_active' => (boolean)$this->is_active,
            'created_at' => $this->created_at != null ? $this->created_at->diffForHumans() : null,
        ];
    }
}
