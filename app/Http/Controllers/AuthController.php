<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Mail\UserRegistered;
use App\Mail\UserRegistereEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'email'         => $request->email,
            'name'          => $request->name,
            'password'      => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        $data = new UserResource($user);

        Mail::to($user->email)->send(new UserRegistered($user));

        return $this->apiResponse($data, $token, 'User Register successfully', 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(LoginRequest $request)
    {
        $user = $request->only('email', 'password');
        $token = Auth::attempt($user);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        $data = new UserResource($user);
        return $this->apiResponse($data, $token, 'User Login successfully', 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
