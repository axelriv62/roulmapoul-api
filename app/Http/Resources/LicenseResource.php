<?php

namespace App\Http\Resources;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin License
 */
class LicenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "num" => $this->num,
            "acquirement_date" => $this->acquirement_date,
            "distribution_date" => $this->distribution_date,
            "country" => $this->country,
            "customer_id" => $this->customer_id
        ];
    }
}
