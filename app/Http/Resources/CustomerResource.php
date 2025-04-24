<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'num' => $this->num,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'num_bill' => $this->num_bill,
            'street_bill' => $this->street_bill,
            'zip_bill' => $this->zip_bill,
            'city_bill' => $this->city_bill,
            'country_bill' => $this->country_bill,
            'user_id' => $this->user_id ?: null,
            'license' => $this->license ? array_diff_key((new LicenseResource($this->license))->toArray($request), array_flip(['customer_id'])) : null,
        ];
    }
}
