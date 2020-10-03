<?php

namespace Modules\Catalogue\Transformers\Repo\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class VariantValueResource extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'variant.value.resource.repo';
    }
}
