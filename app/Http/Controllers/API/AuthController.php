<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewCustomerRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class AuthController extends BaseController
{
    public function register(NewCustomerRegisterRequest $request): JsonResponse
    {
        $validator = $request->validated();

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Cannot register');
        }

        $validatedData = $validator->validated();

        $customerRole = SpatieRole::where('name', Role::CLIENT->value)->first();

        try {
            DB::Transaction(function () use ($customerRole, $validatedData, &$user, &$customer) {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                ])->assignRole($customerRole);

                $customer = Customer::create([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'num' => $validatedData['num'],
                    'street' => $validatedData['street'],
                    'zip' => $validatedData['zip'],
                    'city' => $validatedData['city'],
                    'country' => $validatedData['country'],
                    'user_id' => $user->id,
                ]);
            });
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError(['error' => $e->getMessage()], 'Cannot register', 500);
        }

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['token_type'] = 'Bearer';
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.');
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

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::user()) {
            return $this->sendResponse([], 'All token are already revoqued');
        }
        Auth::user()->tokens()->delete();
        return $this->sendResponse([], 'All token are revoqued');
    }
}
