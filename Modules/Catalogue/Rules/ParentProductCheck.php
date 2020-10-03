<?php

namespace Modules\Catalogue\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Entities\Product;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;

class ParentProductCheck implements Rule
{
    private $category_repo;
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
        if ($value) {
            $parent_product = Product::find($value);
            if ($parent_product && $parent_product->parent_id != null) {
                return false;
            }
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
        return ':attribute cannot be parent';
    }
}
