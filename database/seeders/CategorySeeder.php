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
        Category::factory(11)->sequence(
            ['name' => 'Grocery'],
            ['name' => 'Eat-Out'],
            ['name' => 'Insurance'],
            ['name' => 'Subscription'],
            ['name' => 'Pet'],
            ['name' => 'Utilities'],
            ['name' => 'Gift'],
            ['name' => 'Health'],
            ['name' => 'Home Goods'],
            ['name' => 'Beauty'],
            ['name' => 'Joy'],
        )->create();
    }
}
