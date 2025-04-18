<?php

namespace App\Http\Resources;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Agency
 */
class AgencyResource extends JsonResource
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
            "name" => $this->name, // TODO À ajouter dans l'entité Agency
            "num" => $this->num,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "country" => $this->country,
        ];
    }
}
