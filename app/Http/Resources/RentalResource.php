<?php

namespace App\Http\Resources;

use App\Enums\DocumentType;
use App\Models\Rental;
use Carbon\Carbon;
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
        $url = $this->documents->where('type', DocumentType::BILL)->first()->url ?? null;

        return [
            'id' => $this->id,
            'start' => Carbon::parse($this->start)->format('Y-m-d'),
            'end' => Carbon::parse($this->end)->format('Y-m-d'),
            'nb_days' => $this->nb_days,
            'state' => $this->state,
            'car' => new CarResource($this->car),
            'customer' => new CustomerResource($this->customer),
            'options' => OptionResource::collection($this->options),
            'warranty' => new WarrantyResource($this->warranty),
            'total_price' => $this->total_price,
            'bill_url' => $url ? asset('storage/'.$url) : null,
        ];
    }
}
