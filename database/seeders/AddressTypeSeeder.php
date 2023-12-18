<?php

namespace Database\Seeders;

use App\Models\AddressType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AddressTypeSeeder extends Seeder
{
    private AddressType $model;
    public function __construct(AddressType $model)
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
        $this->model->create(['name' => 'Home', 'slug' => 'home', 'status' => 1]);
        $this->model->create(['name' => 'Work', 'slug' => 'work', 'status' => 1]);
        $this->model->create(['name' => 'Billing', 'slug' => 'billing', 'status' => 1]);
        $this->model->create(['name' => 'Shipping', 'slug' => 'shipping', 'status' => 1]);
        $this->model->create(['name' => 'Others', 'slug' => 'others', 'status' => 1]);
        Schema::enableForeignKeyConstraints();
    }
}
