<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class BillingAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "num_bill" => $this->num_bill,
            "street_bill" => $this->street_bill,
            "zip_bill" => $this->zip_bill,
            "city_bill" => $this->city_bill,
            "country_bill" => $this->country_bill
        ];
    }
}
