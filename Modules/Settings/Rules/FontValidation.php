<?php

namespace Modules\Settings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;

class FontValidation implements Rule
{
    private $allowed_mimes;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $this->allowed_mimes = collect([
            'ttf',
            'otf',
            'woff',
            'fnt',
        ]);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value) {
            $extension = $value->getClientOriginalExtension();
            if ($this->allowed_mimes->contains($extension)){
                return true;
            }
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute should be of type '. implode(',', $this->allowed_mimes->toArray()) ;
    }
}
