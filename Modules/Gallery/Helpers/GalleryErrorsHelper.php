<?php

namespace Modules\Gallery\Helpers;

use Illuminate\Validation\ValidationException;

class GalleryErrorsHelper
{
    public function cannotDeleteImage()
    {
        throw ValidationException::withMessages([
            'promocode' => trans('gallery::errors.cannotDeleteImage'),
        ]);
    }
}
