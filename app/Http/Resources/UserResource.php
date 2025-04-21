<?php

namespace App\Http\Resources;

use App\Enums\Role;
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
        $role = $this->roles->pluck('name')->first();

        if ($role === Role::CLIENT->value) {
            return [
                "id" => $this->id,
                "name" => $this->name,
                "email" => $this->email,
                "role" => $role,
                "customer" => (new CustomerResource($this->customer))->only(['id', 'first_name', 'last_name', 'phone', 'address', 'billing_address'])
            ];
        } else {
            return [
                "id" => $this->id,
                "name" => $this->name,
                "email" => $this->email,
                "role" => $role,
            ];
        }
    }
}
