<?php

namespace Modules\Article\Helpers;


use Illuminate\Validation\ValidationException;

class ArticleHelper
{


    /**
     * @throws ValidationException
     */
    public function duplicateNewsTitle()
    {
        throw ValidationException::withMessages([
            'title' => trans('article::errors.duplicate_slug'),
        ]);
    }

}
