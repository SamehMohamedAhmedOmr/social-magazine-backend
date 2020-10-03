<?php

namespace Modules\WareHouse\Transformers\CMS\Shipment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;

class ShipmentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'company' => CompanyResource::make($this->whenLoaded('shippingCompany')),
            'tracking_number' => $this->tracking_number,
            'tracking_id' => $this->tacking_id,
            'status' => $this->current_status ?? '',
            'receipt' => $this->receipt ? url(\Storage::url('app'. $this->receipt)) : '',
            'order' => OrderResource::make($this->whenLoaded('order')),
        ];
    }
}
