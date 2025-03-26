<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::Factory(10)
            ->sequence(
                ['name' => 'food'],
                ['name' => 'drink'],
                ['name' => 'gas'],
                ['name' => 'insurance'],
                ['name' => 'pet'],
                ['name' => 'health'],
                ['name' => 'entertainment'],
                ['name' => 'subscription'],
            )
            ->create();
    }
}
