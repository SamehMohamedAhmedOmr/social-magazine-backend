<?php

namespace Modules\Settings\Transformers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ConfigurationResource extends Resource
{
    private $setting;
    private $notes;
    private $payment_methods;
    public function __construct($setting, $notes, $payment_methods)
    {
        $this->setting = $setting;
        $this->notes = $notes;
        $this->payment_methods = $payment_methods;

        parent::__construct($setting);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'minimum_selling_price' => ($this->setting) ? $this->setting->minimum_selling_price : 0,
            'maximum_selling_price' => ($this->setting) ? $this->setting->maximum_selling_price : 0,
            'is_free_shipping' => ($this->setting) ? $this->setting->is_free_shipping : 0,
            'free_shipping_minimum_price' => ($this->setting) ? $this->setting->free_shipping_minimum_price : 0,
            'notes' => SystemNotesResource::collection($this->notes),
            'payment_method' => PaymentMethodResource::collection($this->payment_methods)
        ];
    }
}
