<?php

namespace App\Services;

use App\Mail\SuccessfulChangePasswordEmail;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminService extends BaseService
{
    private Admin $model;

    public function __construct(Admin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function fetchAuthUser()
    {
        $userId = auth()->guard('admin')->user()->getAuthIdentifier();
        return $this->findActive($userId);
    }

    public function changePassword(array $data)
    {
        $user = $this->fetchAuthUser();
        $password = ["password" => Hash::make($data['new_password'])];
        $this->update($user->id, $password);

        Mail::to($user['email'])->send(new SuccessfulChangePasswordEmail(["name" => $user['fname'] . " " . $user['lname']]));
    }
}
