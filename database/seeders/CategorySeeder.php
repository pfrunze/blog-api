<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tech'],
            ['name' => 'News'],
            ['name' => 'Sports'],
            ['name' => 'Health'],
            ['name' => 'Travel'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}