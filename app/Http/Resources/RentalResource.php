<?php

namespace App\Http\Resources;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Rental
 */
class RentalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "start" => $this->start,
            "end" => $this->end,
            "nb_days" => $this->nb_days,
            "state" => $this->state,
            "car" => new CarResource($this->car),
            "customer" => new CustomerResource($this->customer),
            "warranty" => $this->warranty->name
        ];
    }
}
