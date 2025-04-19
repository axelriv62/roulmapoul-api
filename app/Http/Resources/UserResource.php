<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            "name" => $this->name,
            "email" => $this->email,
            "role" => $this->roles->pluck('name')->first(),

            // Afficher l'id du client uniquement si l'utilisateur a le rôle 'client'
            "customer_id" => $this->when($this->roles->pluck('name')->contains('client') && $this->customer, $this->customer->id ?? null),
        ];
    }
}
