<?php

namespace Modules\Catalogue\Transformers\Repo\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class ToppingMenuResource extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'topping.resource.repo';
    }
}
