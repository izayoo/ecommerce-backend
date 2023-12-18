<?php

namespace App\Services;

use App\Enum\Constants;
use App\Mail\ForgotPasswordEmail;
use App\Mail\RegistrationEmail;
use App\Mail\SuccessfulChangePasswordEmail;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getToken(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $userDetails = $this->userService->find($user->getAuthIdentifier());
            if ($userDetails->status == Constants::STATUS_INACTIVE) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Account is not verified. Please check your email for the instructions on how to verify your account.'
                ], 400));
            }
            $token = $user->createToken('user_token')->plainTextToken;
            if ($token) {
                $user->last_login = now();
                $user->save();
                return $token;
            }
        }
        return null;
    }

    public function removeToken(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function registerByEmail(array $data)
    {
        $verificationUrl = $data['verification_url'];
        unset($data['password_confirmation']);
        unset($data['verification_url']);
        $data['gender'] = '';
        $data['nationality'] = '';
        $data['account_type'] = Constants::ACCT_TYPE_EMAIL;
        $data['password'] = Hash::make($data['password']);
        $data['status'] = Constants::STATUS_INACTIVE;
        $result = $this->userService->create($data);
        $token = $this->createVerificationToken($result);
        $emailData = $result->toArray();
        $emailData['url'] = $verificationUrl . '?email='
            . $emailData['email'] . '&token=' . $token;

        Mail::to($result['email'])->send(new RegistrationEmail($emailData));

        return $result;
    }

    private function createVerificationToken(User $user)
    {
        $token = sha1(Str::random(27));
        DB::table('password_verifications')->insert([
            "email" => $user->email,
            "token" => $token,
            "created_at" => Carbon::now()
        ]);

        return $token;
    }

    public function changePassword(array $data)
    {
        $user = $this->userService->fetchAuthUser();
        $password = ["password" => Hash::make($data['new_password'])];
        $this->userService->update($user->id, $password);

        Mail::to($user['email'])->send(new SuccessfulChangePasswordEmail(["name" => $user['fname'] . " " . $user['lname']]));
    }

    public function verifyAccount(array $data)
    {
        $query = DB::table('password_verifications')->where("email", $data['email'])
            ->where("token", $data['token']);
        $token = $query->first();

        if ($token) {
            $account = $this->userService->findByEmail($data['email']);
            if ($account) {
                $account->status = Constants::STATUS_ACTIVE;
                $account->verified_at = Carbon::now();
                $account->save();
                $query->delete();

                return true;
            }
        }

        throw new HttpResponseException(response()->json([
            "message" => "Token is expired or invalid."
        ], 400));
    }

    public function forgotPassword(array $data)
    {
        $emailData = $this->userService->findByEmail($data['email'])->toArray();
        $token = $this->createPasswordResetToken($data['email']);
        $emailData['url'] = $data['redirect_url'] . '?email='
            . $emailData['email'] . '&token=' . $token;


        Mail::to($data['email'])->send(new ForgotPasswordEmail($emailData));
    }

    private function createPasswordResetToken(string $email)
    {
        $token = sha1(Str::random(27));
        DB::table('password_resets')->updateOrInsert([
                "email" => $email
            ],[
                "token" => $token,
                "created_at" => Carbon::now()
        ]);

        return $token;
    }

    public function resetPassword(array $data)
    {
        $query = DB::table('password_resets')->where("email", $data['email'])
            ->where("token", $data['token']);
        $token = $query->first();

        if ($token) {
            $account = $this->userService->findByEmail($data['email']);
            if ($account) {
                $account->password = Hash::make($data['password']);
                $account->save();
                $query->delete();

                return true;
            }
        }

        throw new HttpResponseException(response()->json([
            "message" => "Token is expired or invalid."
        ], 400));
    }
}
