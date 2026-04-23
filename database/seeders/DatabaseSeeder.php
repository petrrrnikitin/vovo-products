<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Category::factory(rand(15, 20))
            ->create()
            ->each(function (Category $category) {
                Product::factory(rand(30, 50))
                    ->for($category)
                    ->create();
            });
    }
}