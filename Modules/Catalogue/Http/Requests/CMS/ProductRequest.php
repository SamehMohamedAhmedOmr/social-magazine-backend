<?php

namespace Modules\Catalogue\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalogue\Rules\ParentProductCheck;
use Modules\Catalogue\Rules\ToppingCheck;
use Modules\Catalogue\Rules\VariationCheck;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return $this->method() == 'POST' ? $this->postRequest() : $this->updateRequest();
    }

    private function postRequest(): array
    {
        return [
            ##### Product #####

            ### Relations
            'parent_id' => ['nullable', 'integer', 'exists:products,id', new ParentProductCheck($this)],
            'main_category_id' => 'required|integer|exists:categories,id',
            'unit_of_measure_id' => 'required_with:weight|integer|exists:units_of_measure,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'topping_menu_id' => 'nullable|integer|exists:topping_menus,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'price_lists' => 'nullable|array',
            'price_lists.*.id' => 'integer|exists:price_lists,id',
            'price_lists.*.price' => 'required_with:price_lists.*.id|numeric|min:0|max:134217728',
            'variant_values' => 'nullable|array',
            'variant_values.*' => 'integer|exists:variant_values,id',
            'warehouses' => 'array',
            'warehouses.*.id' => 'integer|exists:warehouses,id',
            'warehouses.*.available' => 'required_with:warehouses.*.id|boolean',
            'warehouses.*.qty' => 'required_if:available.*.available,false|integer|min:0|max:999999999',
            'tags' => 'array|max:100',
            'tags.*' => 'string|min:2|max:254',

            ### Names
            'names' => 'required|array',
            'names.*.name' => 'required|string|max:250',
            'names.*.language' => "required|string|max:4|exists:languages,iso",
            'description' => 'nullable|array',
            'description.*.description' => 'nullable|string|max:4000',
            'description.*.language' => "string|max:4",

            ### Boolean
            'is_active' => 'sometimes|boolean',
            'is_bundle' => 'sometimes|boolean',
            'is_topping' => ['sometimes', 'boolean', new ToppingCheck($this)],
            'is_sell_with_availability' => 'sometimes|boolean',

            ### Object
            'sku' => 'sometimes|unique:products,sku',
            'weight' => 'required_with:unit_of_measure_id|numeric|min:0|max:134217728', // 2^27,
            'max_quantity_per_order' => 'required_if:is_sell_with_availability,true|integer|max:100000',

            ##### Variations #####
            'variations' => ['nullable', 'array', new VariationCheck($this)],
            'variations.*.main_category_id' => 'required|integer|exists:categories,id',
            'variations.*.unit_of_measure_id' => 'integer|exists:units_of_measure,id',
            'variations.*.brand_id' => 'nullable|integer|exists:brands,id',
            'variations.*.topping_menu_id' => 'nullable|integer|exists:topping_menus,id',
            'variations.*.categories' => 'nullable|array',
            'variations.*.categories.*' => 'integer|exists:categories,id',
            'variations.*.price_lists' => 'nullable|array',
            'variations.*.price_lists.*.id' => 'integer|exists:price_lists,id',
            'variations.*.price_lists.*.price' => 'numeric|min:0|max:134217728',
            'variations.*.variant_values' => 'nullable|array',
            'variations.*.variant_values.*' => 'integer|exists:variant_values,id',

            ### Names
            'variations.*.names' => 'required|array',
            'variations.*.names.*.name' => 'required|string|max:250',
            'variations.*.names.*.language' => "required|string|max:4|exists:languages,iso",
            'variations.*.description' => 'nullable|array',
            'variations.*.description.*.description' => 'nullable|string|max:4000',
            'variations.*.description.*.language' => "string|max:4",

            ### Boolean
            'variations.*.is_active' => 'sometimes|boolean',
            'variations.*.is_bundle' => 'sometimes|boolean',
            'variations.*.is_topping' => 'sometimes|boolean',

            ### Object
            'variations.*.sku' => 'sometimes|unique:products,sku',
            'variations.*.weight' => 'numeric|min:0|max:134217728', // 2^27,

        ];
    }

    private function updateRequest() : array
    {
        return [
            ##### Product #####

            ### Relations
            'parent_id' => ['nullable', 'integer', 'exists:products,id', new ParentProductCheck($this)],
            'main_category_id' => 'nullable|integer|exists:categories,id',
            'unit_of_measure_id' => 'nullable|integer|exists:units_of_measure,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'topping_menu_id' => 'nullable|integer|exists:topping_menus,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'price_lists' => 'nullable|array',
            'price_lists.*.id' => 'integer|exists:price_lists,id',
            'price_lists.*.price' => 'numeric|min:0|max:134217728',
            'variant_values' => 'nullable|array',
            'variant_values.*' => 'integer|exists:variant_values,id',
            'warehouses' => 'nullable|array',
            'warehouses.*.id' => 'integer|exists:warehouses,id',
            'warehouses.*.available' => 'required_with:warehouses.*.id,boolean',
            'warehouses.*.qty' => 'required_if:available.*.available,false|integer|min:0|max:999999999',
            'tags' => 'array|max:100',
            'tags.*' => 'string|min:2|max:254',

            ### Names
            'names' => 'sometimes|required|array',
            'names.*.name' => 'string|max:250',
            'names.*.language' => "required_with:names.*.name|string|max:4|exists:languages,iso",
            'description' => 'nullable|array',
            'description.*.description' => 'nullable|string|max:4000',
            'description.*.language' => "string|max:4",

            ### Boolean
            'is_active' => 'sometimes|boolean',
            'is_bundle' => 'sometimes|boolean',
            'is_topping' => ['sometimes', 'boolean', new ToppingCheck($this)],
            'is_sell_with_availability' => 'sometimes|boolean',

            ### Object
            'sku' => 'sometimes|unique:products,sku,'.$this->product,
            'weight' => 'numeric|min:0|max:134217728', // 2^27,
            'max_quantity_per_order' => 'sometimes|integer|max:100000',

            ##### Variations #####
            'variations' => ['nullable', 'array', new VariationCheck($this)],
            'variations.*.id' => 'sometimes|integer',
            'variations.*.main_category_id' => 'required_without:variations.*.id|integer|exists:categories,id',
            'variations.*.unit_of_measure_id' => 'integer|exists:units_of_measure,id',
            'variations.*.brand_id' => 'nullable|integer|exists:brands,id',
            'variations.*.topping_menu_id' => 'nullable|integer|exists:topping_menus,id',
            'variations.*.categories' => 'nullable|array',
            'variations.*.categories.*' => 'integer|exists:categories,id',
            'variations.*.price_lists' => 'nullable|array',
            'variations.*.price_lists.*.id' => 'integer|exists:price_lists,id',
            'variations.*.price_lists.*.price' => 'numeric|min:0|max:134217728',
            'variations.*.variant_values' => 'nullable|array',
            'variations.*.variant_values.*' => 'integer|exists:variant_values,id',

            ### Names
            'variations.*.names' => 'required_without:variations.*.id|array',
            'variations.*.names.*.name' => 'required_without:variations.*.id|string|max:250',
            'variations.*.names.*.language' => "required_without:variations.*.id|string|max:4|exists:languages,iso",
            'variations.*.description' => 'nullable|array',
            'variations.*.description.*.description' => 'nullable|string|max:4000',
            'variations.*.description.*.language' => "string|max:4",

            ### Boolean
            'variations.*.is_active' => 'sometimes|boolean',
            'variations.*.is_bundle' => 'sometimes|boolean',
            'variations.*.is_topping' => 'sometimes|boolean',

            ### Object
            'variations.*.sku' => 'sometimes',
            'variations.*.weight' => 'numeric|min:0|max:134217728', // 2^27,
        ];
    }

    public function attributes()
    {
        return [
            'price_lists.*.price' => 'price',
            'variant_values.*' => 'variant value',
            'variations.*.price_lists.*.price' => 'variations price',
            'variations.*.weight' => 'weight',
            'variations.*.variant_values.*' => 'variations variant value',
        ];
    }
}
