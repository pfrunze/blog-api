<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            throw new \Exception('Run UserSeeder and CategorySeeder before ArticleSeeder.');
        }

        $articlesData = [
            [
                'category_id' => $categories->where('name', 'Tech')->first()->id,
                'user_id' => $users->get(0)->id,
                'title' => 'Tech Article 1',
                'description' => 'A deep dive into the latest tech trends.',
            ],
            [
                'category_id' => $categories->where('name', 'Tech')->first()->id,
                'user_id' => $users->get(1)->id,
                'title' => 'Tech Article 2',
                'description' => 'Exploring AI advancements.',
            ],
            [
                'category_id' => $categories->where('name', 'News')->first()->id,
                'user_id' => $users->get(2)->id,
                'title' => 'News Article 1',
                'description' => 'Breaking news from around the world.',
            ],
            [
                'category_id' => $categories->where('name', 'News')->first()->id,
                'user_id' => $users->get(3)->id,
                'title' => 'News Article 2',
                'description' => 'Local news updates.',
            ],
            [
                'category_id' => $categories->where('name', 'Sports')->first()->id,
                'user_id' => $users->get(4)->id,
                'title' => 'Sports Article 1',
                'description' => 'Recap of the latest match.',
            ],
            [
                'category_id' => $categories->where('name', 'Health')->first()->id,
                'user_id' => $users->get(0)->id,
                'title' => 'Health Article 1',
                'description' => 'Tips for a healthy lifestyle.',
            ],
            [
                'category_id' => $categories->where('name', 'Health')->first()->id,
                'user_id' => $users->get(1)->id,
                'title' => 'Health Article 2',
                'description' => 'Understanding mental health.',
            ],
            [
                'category_id' => $categories->where('name', 'Travel')->first()->id,
                'user_id' => $users->get(2)->id,
                'title' => 'Travel Article 1',
                'description' => 'Top destinations for 2025.',
            ],
            [
                'category_id' => $categories->where('name', 'Travel')->first()->id,
                'user_id' => $users->get(3)->id,
                'title' => 'Travel Article 2',
                'description' => 'A guide to budget travel.',
            ],
            [
                'category_id' => $categories->where('name', 'Sports')->first()->id,
                'user_id' => $users->get(4)->id,
                'title' => 'Sports Article 2',
                'description' => 'Training tips for athletes.',
            ],
        ];

        foreach ($articlesData as $articleData) {
            $article = Article::create(array_merge($articleData, [
                'up_vote_count' => 0,
                'down_vote_count' => 0,
            ]));

            $this->seedVotes($article, $users);
        }
    }

    protected function seedVotes(Article $article, $users): void
    {
        $votingUsers = $users->random(rand(0, $users->count()));

        $upVotes = 0;
        $downVotes = 0;

        foreach ($votingUsers as $user) {
            $voteType = rand(0, 1) ? 'up' : 'down';

            $article->votes()->attach($user->id, ['vote_type' => $voteType]);

            if ($voteType === 'up') {
                $upVotes++;
            } else {
                $downVotes++;
            }
        }

        $article->up_vote_count = $upVotes;
        $article->down_vote_count = $downVotes;
        $article->save();
    }
}