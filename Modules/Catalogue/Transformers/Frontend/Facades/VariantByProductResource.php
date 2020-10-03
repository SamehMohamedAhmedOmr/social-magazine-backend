<?php

namespace Modules\Catalogue\Transformers\Frontend\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class VariantByProductResource extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'variant.resource.front';
    }
}
