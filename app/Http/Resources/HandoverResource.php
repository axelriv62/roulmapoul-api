<?php

namespace App\Http\Resources;

use App\Models\Handover;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Handover
 */
class HandoverResource extends JsonResource
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
            'datetime' => $this->datetime->toDateTimeString(),
            'mileage' => $this->mileage,
            'fuel_level' => $this->fuel_level,
            'interior_condition' => $this->interior_condition,
            'exterior_condition' => $this->exterior_condition,
            'comment' => $this->comment,
            'rental_id' => $this->rental_id,
            'customer_id' => $this->customer_id,
        ];
    }
}
