<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends BaseController
{
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

    public function me(Request $request): JsonResponse
    {
        $user = new UserResource(Auth::user());
        return $this->sendResponse($user, 'User retrieve with success');
    }

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::user()) {
            return $this->sendResponse([], 'All token are already revoqued');
        }
        Auth::user()->tokens()->delete();
        return $this->sendResponse([], 'All token are revoqued');
    }
}
