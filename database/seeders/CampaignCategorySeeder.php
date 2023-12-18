<?php

namespace Database\Seeders;

use App\Models\CampaignCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CampaignCategorySeeder extends Seeder
{
    private CampaignCategory $model;

    public function __construct(CampaignCategory $model)
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
            'name' => 'Tech',
            'slug' => 'tech',
            'status' => 1,
        ]);
        $this->model->create([
            'name' => 'Cash',
            'slug' => 'cash',
            'status' => 1,
        ]);
        $this->model->create([
            'name' => 'Getaways',
            'slug' => 'getaways',
            'status' => 1,
        ]);
        $this->model->create([
            'name' => 'Experiences',
            'slug' => 'experiences',
            'status' => 1,
        ]);
        $this->model->create([
            'name' => 'Automotive',
            'slug' => 'automotive',
            'status' => 1,
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
