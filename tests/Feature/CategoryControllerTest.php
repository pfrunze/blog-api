<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Domain\Category\Actions\GetTopCategoriesAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::clear();

        $category1 = Category::factory()->create(['name' => 'Tech']);
        $category2 = Category::factory()->create(['name' => 'News']);
        $category3 = Category::factory()->create(['name' => 'Sports']);
        $category4 = Category::factory()->create(['name' => 'Health']);
        $category5 = Category::factory()->create(['name' => 'Travel']);
        $category6 = Category::factory()->create(['name' => 'Food']);

        Article::factory()->create([
            'category_id' => $category1->id,
            'title' => 'Tech Article 1',
            'up_vote_count' => 100,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category1->id,
            'title' => 'Tech Article 2',
            'up_vote_count' => 90,
            'down_vote_count' => 5,
        ]);
        Article::factory()->create([
            'category_id' => $category1->id,
            'title' => 'Tech Article 3',
            'up_vote_count' => 80,
            'down_vote_count' => 0,
        ]);

        Article::factory()->create([
            'category_id' => $category2->id,
            'title' => 'News Article 1',
            'up_vote_count' => 105,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category2->id,
            'title' => 'News Article 2',
            'up_vote_count' => 90,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category3->id,
            'title' => 'Sports Article 1',
            'up_vote_count' => 80,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category4->id,
            'title' => 'Health Article 1',
            'up_vote_count' => 98,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category4->id,
            'title' => 'Health Article 2',
            'up_vote_count' => 85,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category4->id,
            'title' => 'Health Article 3',
            'up_vote_count' => 80,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category4->id,
            'title' => 'Health Article 4',
            'up_vote_count' => 75,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category5->id,
            'title' => 'Travel Article 1',
            'up_vote_count' => 102,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category5->id,
            'title' => 'Travel Article 2',
            'up_vote_count' => 90,
            'down_vote_count' => 5,
        ]);
        Article::factory()->create([
            'category_id' => $category6->id,
            'title' => 'Food Article 1',
            'up_vote_count' => 97,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category6->id,
            'title' => 'Food Article 2',
            'up_vote_count' => 92,
            'down_vote_count' => 10,
        ]);
        Article::factory()->create([
            'category_id' => $category6->id,
            'title' => 'Food Article 3',
            'up_vote_count' => 88,
            'down_vote_count' => 10,
        ]);
    }

    public function test_top_categories_endpoint()
    {
        $response = $this->getJson('/api/top-categories');
        $response->assertStatus(200);

        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'articles_count',
                ],
            ],
        ]);

        $data = $response->json('data');
        $this->assertEquals('News', $data[0]['name']);
        $this->assertEquals('Travel', $data[1]['name']);
        $this->assertEquals('Tech', $data[2]['name']);
        $this->assertEquals('Health', $data[3]['name']);
        $this->assertEquals('Food', $data[4]['name']);

        $this->assertEquals(2, $data[0]['articles_count']);
        $this->assertEquals(2, $data[1]['articles_count']);
        $this->assertEquals(3, $data[2]['articles_count']);
        $this->assertEquals(4, $data[3]['articles_count']);
        $this->assertEquals(3, $data[4]['articles_count']);
    }

    public function test_top_categories_caching()
    {
        Cache::clear();

        $response = $this->getJson('/api/top-categories');
        $response->assertStatus(200);

        $this->assertTrue(Cache::has('top_categories'));

        $cachedData = Cache::get('top_categories');

        $this->assertEquals($cachedData, $response->json('data'));
    }
}