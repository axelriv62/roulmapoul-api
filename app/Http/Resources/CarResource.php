<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Car
 */
class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plate' => $this->plate,
            'availability' => $this->availability,
            'price_day' => $this->price_day,
            'category' => new CategoryResource($this->category),
            'agency' => new AgencyResource($this->agency),
        ];
    }
}
