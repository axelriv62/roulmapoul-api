<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\LinkUserCustomerRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class AuthController extends BaseController
{
    public function registerCustomer(LinkUserCustomerRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $customerRole = SpatieRole::where('name', Role::CLIENT->value)->first();

        $user = User::create([
            'name' => $request->validated()['name'],
            'email' => $customer->email,
            'password' => Hash::make($request->validated()['password']),
        ]);
        $user->assignRole($customerRole);

        $customer->user_id = $user->id;
        $customer->save();

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $request->validated()['name'];
        $success['token_type'] = 'Bearer';

        return $this->sendResponse($success, 'Utilisateur créé avec succès.');
    }

    public function registerAgent(NewUserRequest $request): JsonResponse
    {
        if (Auth::user()->cannot('createAgent', User::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à performer cette opération.', 403);
        }

        $agentRole = SpatieRole::where('name', Role::AGENT->value)->first();

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ])->assignRole($agentRole);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $request['name'];
        $success['token_type'] = 'Bearer';

        return $this->sendResponse($success, 'Agent créé avec succès.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request['email'])->first();

        if (!$user instanceof User) {
            return $this->sendError([], 'Cannot find this user', 404);
        }

        if (!Hash::check($request['password'], $user->password)) {
            return $this->sendError([], 'Invalid credential', 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Token generate with success');
    }

    public function me(): JsonResponse
    {
        $user = new UserResource(Auth::user());
        return $this->sendResponse($user, 'User retrieve with success');
    }

    public function logout(): JsonResponse
    {
        if (!Auth::user()) {
            return $this->sendResponse([], 'All token are already revoqued');
        }
        Auth::user()->tokens()->delete();
        return $this->sendResponse([], 'All token are revoqued');
    }
}
