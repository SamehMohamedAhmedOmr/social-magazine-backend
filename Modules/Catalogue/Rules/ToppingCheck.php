<?php

namespace Modules\Catalogue\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class ToppingCheck implements Rule
{
    private $request;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        if ($this->request->has('is_topping')
            && $this->request->is_topping && $this->request->topping_menu_id != null) {
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
        return ':attribute cannot be send with topping_menu_id';
    }
}
