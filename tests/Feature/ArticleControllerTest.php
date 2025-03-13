<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $connectionsToTransact = [];

    protected $user1;
    protected $user2;
    protected $user3;
    protected $user4;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->category = Category::factory()->create(['name' => 'Tech']);
    }

    public function test_all_articles_with_filters()
    {
        Passport::actingAs($this->user1);

        Article::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->user1->id,
            'title' => 'Tech Article 1',
            'description' => 'Tech description',
        ]);
        Article::factory()->create([
            'category_id' => $this->category->id,
            'user_id' => $this->user1->id,
            'title' => 'Another Article',
            'description' => 'Another description',
        ]);

        $response = $this->getJson('/api/articles?category_id=' . $this->category->id);
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Tech Article 1'])
            ->assertJsonCount(2, 'data');

        $response = $this->getJson('/api/articles?filter[search]=Tech');
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Tech Article 1'])
            ->assertJsonMissing(['title' => 'Another Article']);
    }

    public function test_create_article()
    {
        Passport::actingAs($this->user2);

        $response = $this->postJson('/api/articles', [
            'category_id' => $this->category->id,
            'title' => 'Test Article',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'rating'
                ],
            ]);
    }

    public function test_vote_article()
    {
        $article = Article::factory()->create(['user_id' => $this->user3->id]);
        Passport::actingAs($this->user3);

        $response = $this->postJson("/api/articles/{$article->id}/vote", ['vote_type' => 'up']);

        $this->assertEquals($article->up_vote_count + 1, $article->fresh()->up_vote_count);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'up_vote_count',
                    'down_vote_count',
                    'rating'
                ],
            ]);
    }

    public function test_user_can_update_own_article()
    {
        $article1 = Article::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Article by User3',
            'up_vote_count' => 100,
            'down_vote_count' => 10,
            'user_id' => $this->user3->id,
        ]);
        Passport::actingAs($this->user3);

        $response = $this->putJson("/api/articles/{$article1->id}", [
            'title' => 'Updated Article by User3',
            'description' => 'Updated description',
            'category_id' => $this->category->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['title' => 'Updated Article by User3']]);
    }

    public function test_user_cannot_update_another_users_article()
    {
        $article2 = Article::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Article by User4',
            'up_vote_count' => 90,
            'down_vote_count' => 5,
            'user_id' => $this->user4->id,
        ]);

        Passport::actingAs($this->user3);

        $response = $this->putJson("/api/articles/{$article2->id}", [
            'title' => 'Updated Article by User4',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_article()
    {
        $article1 = Article::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Article by User3',
            'up_vote_count' => 100,
            'down_vote_count' => 10,
            'user_id' => $this->user3->id,
        ]);
        Passport::actingAs($this->user3);

        $response = $this->deleteJson("/api/articles/{$article1->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('articles', ['id' => $article1->id]);
    }

    public function test_user_cannot_delete_another_users_article()
    {
        $article2 = Article::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Article by User4',
            'up_vote_count' => 90,
            'down_vote_count' => 5,
            'user_id' => $this->user4->id,
        ]);

        Passport::actingAs($this->user3);

        $response = $this->deleteJson("/api/articles/{$article2->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', ['id' => $article2->id]);
    }
}