<?php

namespace Database\Seeders;

use App\Models\BannerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BannerTypeSeeder extends Seeder
{
    private BannerType $model;

    public function __construct(BannerType $model)
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
        $this->model->create(['name' => 'Carousel Banner', 'slug' => 'carousel-banner', 'status' => 1]);
        $this->model->create(['name' => 'Left Small Banner', 'slug' => 'left-small-banner', 'status' => 1]);
        $this->model->create(['name' => 'Right Small Banner', 'slug' => 'right-small-banner', 'status' => 1]);
        Schema::enableForeignKeyConstraints();
    }
}
