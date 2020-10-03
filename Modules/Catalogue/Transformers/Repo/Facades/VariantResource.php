<?php

namespace Modules\Catalogue\Transformers\Repo\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class VariantResource extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'variant.resource.repo';
    }
}
