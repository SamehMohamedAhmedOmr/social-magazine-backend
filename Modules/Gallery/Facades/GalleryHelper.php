<?php


namespace Modules\Gallery\Facades;

use Illuminate\Support\Facades\Facade;

class GalleryHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GalleryHelper';
    }
}
