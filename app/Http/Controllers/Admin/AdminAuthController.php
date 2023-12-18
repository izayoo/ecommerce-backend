<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            $token = $user->createToken('admin_token')->plainTextToken;
            if ($token) {
               $user->last_login = now();
               $user->save();
            }
            return response()->json([
                'message' => 'Successfully logged in',
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function fetchAccountDetails()
    {
        return response()->json([
            'data' => $this->adminService->fetchAuthUser()
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->adminService->changePassword($request->only(['new_password']));

        return response()->json([
            "message" => "Successfully changed password"
        ]);
    }
}
