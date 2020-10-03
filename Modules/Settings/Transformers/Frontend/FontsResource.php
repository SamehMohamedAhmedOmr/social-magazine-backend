<?php

namespace Modules\Settings\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class FontsResource extends Resource
{
    public static function getFile($resource = null){
        return isset($resource) ? getFilePath('fonts', $resource->font_path) : null;
    }
}
