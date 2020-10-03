<?php

namespace Modules\FrontendUtilities\Transformers;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @property mixed iso
 * @property mixed pivot
 */
class CollectionLanguageResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'lang' => $this->iso ,
            'title' => $this->pivot->title,
        ];
    }
}
