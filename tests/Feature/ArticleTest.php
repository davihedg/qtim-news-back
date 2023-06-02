<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->author = User::factory()->create();
        $this->user = User::factory()->create();
    }

    public function test_articles_index_success()
    {
        $response = $this->getJson(route('articles.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_articles_show_success()
    {
        $article = Article::factory()->create([
            'author_id' => $this->author->id
        ]);

        $response = $this->getJson(route('articles.index', $article));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_articles_show_not_found()
    {
        Article::factory()->count(10)->create();

        $notExistsArticleID = Article::orderByDEsc('id')->first()->id + 1;
        $response = $this->getJson(route('articles.show', ['article' => $notExistsArticleID]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_articles_store_success()
    {
        Sanctum::actingAs($this->author);

        $response = $this->postJson(route('articles.store'), [
            'title' => 'title',
            'subtitle' => 'subtitle',
            'text' => 'text'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_articles_update_success()
    {
        Sanctum::actingAs($this->author);

        $article = Article::factory()->create([
            'author_id' => $this->author->id
        ]);

        $response = $this->putJson(route('articles.update', $article), [
            'title' => 'title update',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_articles_update_forbidden()
    {
        Sanctum::actingAs($this->user);

        $article = Article::factory()->create([
            'author_id' => $this->author->id
        ]);

        $response = $this->putJson(route('articles.update', $article), [
            'title' => 'title update',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    public function test_articles_destroy_success()
    {
        Sanctum::actingAs($this->author);

        $article = Article::factory()->create([
            'author_id' => $this->author->id
        ]);

        $response = $this->deleteJson(route('articles.destroy', $article));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_articles_destroy_forbidden()
    {
        Sanctum::actingAs($this->user);

        $article = Article::factory()->create([
            'author_id' => $this->author->id
        ]);

        $response = $this->deleteJson(route('articles.destroy', $article));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
