<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::insert([
            ['code' => 'MYR', 'name' => 'Malaysian Ringgit'],
            ['code' => 'USD', 'name' => 'US Dollar'],
        ]);
    }
}
