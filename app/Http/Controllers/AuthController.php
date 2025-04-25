<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\LinkUserCustomerRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class AuthController extends BaseController
{
    /**
     * Enregistre un client en tant qu'utilisateur.
     *
     * @param  LinkUserCustomerRequest  $request  La requête de création de l'utilisateur.
     * @param  string  $id  L'identifiant du client.
     */
    public function registerCustomer(LinkUserCustomerRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $customerRole = SpatieRole::where('name', Role::CUSTOMER->value)->first();

        $user = User::create([
            'name' => $request->validated()['name'],
            'email' => $customer->email,
            'password' => Hash::make($request->validated()['password']),
        ]);
        $user->assignRole($customerRole);

        $customer->user_id = $user->id;
        $customer->save();

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['token_type'] = 'Bearer';

        return $this->sendResponse($success, 'Utilisateur créé avec succès.');
    }

    /**
     * Enregistre un utilisateur agent.
     *
     * @param  NewUserRequest  $request  La requête de création de l'utilisateur.
     */
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

        $success['user'] = new UserResource($user);
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['token_type'] = 'Bearer';

        return $this->sendResponse($success, 'Agent créé avec succès.');
    }

    /**
     * Connecte l'utilisateur.
     *
     * @param  LoginRequest  $request  La requête de connexion.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request['email'])->first();

        if (! $user instanceof User) {
            return $this->sendError([], 'Utilisateur non trouvé', 404);
        }

        if (! Hash::check($request['password'], $user->password)) {
            return $this->sendError([], 'Mot de passe incorrect', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $success['user'] = new UserResource($user);
        $success['token'] = $token;
        $success['token_type'] = 'Bearer';

        return $this->sendResponse($success, 'Token généré avec succès');
    }

    /**
     * Récupère les infos du client connecté.
     */
    public function me(): JsonResponse
    {
        $user = Auth::user();
        $success['user'] = new UserResource($user);

        return $this->sendResponse($success, 'Utilisateur récupéré avec succès');
    }

    /**
     * Déconnecte l'utilisateur connecté.
     */
    public function logout(): JsonResponse
    {
        if (! Auth::user()) {
            return $this->sendResponse([], 'Tous les tokens ont déjà été révoqués');
        }
        Auth::user()->tokens()->delete();

        return $this->sendResponse([], 'Tous les tokens ont été révoqués avec succès');
    }

    /**
     * Récupère le client associé à l'utilisateur connecté
     */
    public function myCustomer(): JsonResponse
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        $success['customer'] = new CustomerResource($customer);

        return $this->sendResponse($success, 'Client récupéré avec succès');
    }
}
