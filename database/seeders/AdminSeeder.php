<?php

namespace Database\Seeders;

use App\Enum\Constants;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    private Admin $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->model->truncate();
        $this->model->create([
            'fname' => 'Admin',
            'lname' => '',
            'email' => 'admin@admin.com',
            'password' => Hash::make('asdfasdf%%$'),
            'status' => Constants::STATUS_ACTIVE
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
