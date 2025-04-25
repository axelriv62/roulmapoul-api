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

        if ($role === Role::CUSTOMER->value) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'role' => $role,
                'customer' => array_diff_key((new CustomerResource($this->customer))->toArray($request), array_flip(['email', 'user_id'])),
            ];
        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'role' => $role,
            ];
        }
    }
}
