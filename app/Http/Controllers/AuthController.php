<?php

namespace App\Http\Controllers;

use App\Enum\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private AuthService $authService;
    private UserService $userService;

    public function __construct(
        AuthService $authService,
        UserService $userService
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = $this->authService->getToken($credentials);

        if ($token) {
            return response()->json([
                'message' => 'Successfully logged in',
                'token' => $token,
                'data' => $this->userService->fetchAuthUser()
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $this->authService->removeToken($request);
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->registerByEmail($request->all());
        return response()->json([
            'message' => 'Registration successful.',
            'data' => $data
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword($request->only(['new_password']));

        return response()->json([
            "message" => "Successfully changed password"
        ]);
    }

    public function verifyAccount(VerifyAccountRequest $request)
    {
        $this->authService->verifyAccount($request->all());

        return response()->json([
            "message" => "Successfully verified account"
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->authService->forgotPassword($request->all());

        return response()->json([
            "message" => "Successfully requested password change."
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->authService->resetPassword($request->all());

        return response()->json([
            "message" => "Successfully changed password. Please Login."
        ]);
    }


    public function socialLogin(Request $request)
    {
        $data = $request->all();
        $this->validateProvider($data['provider']);

        $userCreated = User::firstOrCreate(
            [
                'email' => $data['email']
            ],
            [
                'verified_at' => now(),
                'fname' => $data['name'],
                'status' => Constants::STATUS_ACTIVE,
            ]
        );

        $token = $userCreated->createToken('user_token')->plainTextToken;

        if ($token) {
            return response()->json([
                'message' => 'Successfully logged in',
                'token' => $token,
                'data' => $this->userService->fetchAuthUser()
            ], 200);
        }

        return response()->json(['message' => 'Invalid account'], 401);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            throw new HttpResponseException(response()
                ->json(['error' => 'Please login using facebook or google'], 422));
        }
    }
}
