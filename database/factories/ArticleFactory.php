<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'up_vote_count' => $this->faker->numberBetween(0, 100),
            'down_vote_count' => $this->faker->numberBetween(0, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}