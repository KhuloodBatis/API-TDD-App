<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private string $routePrefix = 'articles.';
    /**@test */
    public function test_can_get_all_articles()
    {
        $articles = Article::factory()->create();
        $response = $this->getJson(route($this->routePrefix . 'index'),  $articles->toArray());
        $response->assertOk();
        $this->assertDatabaseHas(
            'articles',
            $articles->toArray()
        );
    }

    /**@test */
    public function test_get_single_article()
    {
        $article = article::factory()->create();

        $response = $this->getJson(
            route($this->routePrefix . 'show', $article->id),
            $article->toArray()
        );
        $response->assertOk();
        $this->assertDatabaseHas(
            'articles',
            $article->toArray()
        );
    }

    /**@test */
    public function test_it_can_create_an_article()
    {
        $article = Article::factory()->make();
        $response = $this->postJson(
            route($this->routePrefix . 'store'),
            $article->toArray()
        );
        $response->assertCreated();
        $response->assertJson([
            'data' => ['title' => $article->title]
        ]);
        $this->assertDatabaseHas(
            'articles',
            $article->toArray()
        );
    }

    /**@test */
    public function test_can_update_a_article()
    {
        $this->withExceptionHandling();

        $oldArticle = Article::factory()->create();
        $newArticle = Article::factory()->make();

        $response = $this->putJson(
            route(
                $this->routePrefix . 'update',
                $oldArticle->id
            ),
            $newArticle->toArray()
        );
        $response->assertJson([
            'data' => [
                'id' => $oldArticle->id,
                'title' => $newArticle->title
            ]
        ]);
        $this->assertDatabaseHas(
            'articles',
            $newArticle->toArray()
        );
    }

    /**@test */
    public function test_can_delete_an_article()
    {
        $exsistArticle = Article::factory()->create();

        $this->deleteJson(
            route(
                $this->routePrefix . 'destroy',
                $exsistArticle->id
            )
        )->assertNoContent();
        $this->assertDatabaseMissing(
            'articles',
            $exsistArticle->toArray()
        );
    }
}
