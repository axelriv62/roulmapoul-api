<?php

namespace App\Http\Resources;

use App\Models\License;
use Carbon\Carbon;
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
            'num' => $this->num,
            'birthday' => (new Carbon($this->birthday))->format('Y-m-d'),
            'acquirement_date' => (new Carbon($this->acquirement_date))->format('Y-m-d'),
            'distribution_date' => (new Carbon($this->distribution_date))->format('Y-m-d'),
            'country' => $this->country,
            'customer_id' => $this->customer_id,
        ];
    }
}
