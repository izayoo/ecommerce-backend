<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductCategorySeeder extends Seeder
{
    private ProductCategory $model;

    public function __construct(ProductCategory $model)
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
            'name' => 'Stationary',
            'slug' => 'stationary',
            'status' => 1
        ]);
        $this->model->create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'status' => 1
        ]);
        $this->model->create([
            'name' => 'Others',
            'slug' => 'others',
            'status' => 1
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
