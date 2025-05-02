<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Sample Banner',
            'description' => 'This is a sample banner',
            'category_id' => 1,
            'status' => 1,
        ]);
    }
}
