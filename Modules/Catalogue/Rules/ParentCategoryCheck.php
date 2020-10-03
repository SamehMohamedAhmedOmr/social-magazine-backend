<?php

namespace Modules\Catalogue\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;

class ParentCategoryCheck implements Rule
{
    private $category_repo;
    private $request;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
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
            $parent_category = Category::find($value);
            if ($parent_category && $parent_category->parent_id == $this->request->category) {
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
