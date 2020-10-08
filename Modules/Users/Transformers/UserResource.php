<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed admin
 * @property mixed user_type
 * @property mixed client
 * @property mixed name
 * @property mixed email
 * @property mixed status
 * @property mixed token_last_renew
 */
class UserResource extends Resource
{
    private $token;
    private $expires_at;
    private $is_register;


    public function __construct($resource, $token = null, $expires_at = null, $is_register = false)
    {
        $this->token = $token;
        $this->expires_at = $expires_at;
        $this->is_register = $is_register;
        parent::__construct($resource);
    }


    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $token = [];

        if ((Auth::id() == $this->id || $this->is_register == true) && isset($this->token) && isset($this->expires_at)) {
            $token = [
                'token' => $this->token,
                'expire_at' => $this->expires_at,
            ];
        }

        $default = [
            'first_name' => $this->first_name,
            'family_name' => $this->family_name,
            'email' => $this->email,
            'account_types' => AccountTypeResource::collection($this->whenLoaded('accountTypes')),
        ];

        return array_merge($token, $default, $data);
    }
}
