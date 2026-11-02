<?php

namespace App\Http\Controllers;

use App\Contract\UserInterface\UserCreatorInterface;
use App\Contracts\UserInterface\UserFinderInterface;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Http\Requests\Auth\ApiRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repository\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserCreatorInterface $userCreator,
        private UserFinderInterface $userFinder
    ) {}
    
    public function login(ApiLoginRequest $request): JsonResponse {
        $validated = $request->validated();

        $user = $this->userFinder->getByEmail($validated['email']);
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->ok([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ], 'Login success.', 200);
    }

    public function register(ApiRegisterRequest $request): JsonResponse {
        $validated = $request->validated();

        // Search other user email for uniqeness
        $existingUser = $this->userFinder->getByEmail($validated['email']);
        if ($existingUser){
            return $this->error('Email already exists.', 409);
        }

        // Check if the password and the confirmation password are match
        if ($validated['password'] !== $validated['confirm_password']) {
            return $this->error("Password and confirmation password doesn't match.", 400);
        }

        // Perform make new user
        $user = $this->userCreator->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->ok([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ], "Register success", 201);
    }

    public function logout(Request $req): JsonResponse {
        $req->user()->currentAccessToken()->delete();

        return $this->ok(null, 'Logout successful');
    }
}
