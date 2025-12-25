<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * User Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->errorResponse('The provided credentials are incorrect.', 401);
            }

            // Using email as device name for simplicity, or grab generic string
            $deviceName = $request->email ?? 'unknown_device';
            $token = $user->createToken($deviceName)->plainTextToken;

            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return $this->successResponse($data, 'Login successful');

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation Failed', 422, $e->errors());

        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    /**
     * User Registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // 1. Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
            ]);

            // 2. Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 3. Generate Token immediately after registration (auto-login)
            $token = $user->createToken('api-register')->plainTextToken;

            // 4. Build Response Data
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return $this->successResponse($data, 'User registered successfully', 201);

        } catch (ValidationException $e) {
            return $this->errorResponse('Validation Failed', 422, $e->errors());

        } catch (Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }

    /**
     * User Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // currentAccessToken() relies on the auth:sanctum middleware
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
                return $this->successResponse(null, 'Logged out successfully');
            }

            return $this->errorResponse('No active session found', 400);

        } catch (Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return $this->errorResponse('Internal Server Error', 500);
        }
    }
}