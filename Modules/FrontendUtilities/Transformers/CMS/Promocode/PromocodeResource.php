<?php

namespace Modules\FrontendUtilities\Transformers\CMS\Promocode;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Users\Transformers\UserSummaryResource;

class PromocodeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "code" => $this->code,
            "minimum_price" => $this->minimum_price,
            "maximum_price" => $this->maximum_price,
            "is_active" => $this->is_active,

            'discount_type' => $this->discount_type,

            "max_discount_amount" => $this->max_discount_amount,
            "reward" => $this->reward,
            "usage_per_user" => $this->usage_per_user,

            "users_count" => $this->users_count,
            "from" => $this->from,
            "to" => $this->to,

            'products' => PromocodeProductResource::collection($this->whenLoaded('products')),
            'brands' => PromocodeBrandResource::collection($this->whenLoaded('brands')),
            'categories' => PromocodeCategoryResource::collection($this->whenLoaded('categories')),
            'users' => UserSummaryResource::collection($this->whenLoaded('users')),
        ];
    }
}
