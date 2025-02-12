<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Private;

use App\Http\Resources\Api\V1\Private\Article\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->endpoint = route('v1.private.articles.index');
    $this->user = User::factory()->create();
    $this->article = Article::factory()->create();
});

// Authentication Tests
it('requires authentication', function () {
    $response = $this->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('returns articles for authenticated user', function () {
    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'url',
                    'content',
                    'author',
                    'extra_data',
                    'published_at',
                ],
            ],
            'message',
            'status',
            'links',
            'meta',
        ])
        ->assertJsonPath('message', __('success'))
        ->assertJsonPath('status', Response::HTTP_OK);
});

// Resource Tests
it('resource returns correct structure', function () {
    $resource = new ArticleResource($this->article);

    $response = $resource->response()->getData(true);

    expect($response)
        ->toHaveKey('data')
        ->and($response['data'])
        ->toHaveKeys([
            'id',
            'title',
            'description',
            'url',
            'content',
            'author',
            'extra_data',
            'published_at',
        ]);
});

// Pagination Tests
it('returns paginated response', function () {
    Article::factory()->count(16)->create();

    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertJsonStructure([
        'meta' => [
            'current_page',
            'from',
            'path',
            'per_page',
            'to',
        ],
    ]);

    expect($response->json('meta.per_page'))->toBe(15)
        ->and($response->json('meta.current_page'))->toBe(1)
        ->and($response->json('data'))->toHaveCount(15);
});

// Filter Tests
it('can filter articles by title', function () {
    Article::factory()->create(['title' => 'Target Article']);
    Article::factory()->count(3)->create();

    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint.'?filter[title]=Target');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Target Article');
});

// Sorting Tests
it('sorts articles by published_at in descending order by default', function () {
    $oldArticle = Article::factory()->create([
        'published_at' => now()->subDays(2),
    ]);

    $newArticle = Article::factory()->create([
        'published_at' => now(),
    ]);

    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonPath('data.0.id', $newArticle->id)
        ->assertJsonPath('data.1.id', $oldArticle->id);
});

// API Specific Tests
it('returns json response', function () {
    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertHeader('Content-Type', 'application/json');
});

it('respects rate limiting', function () {
    for ($i = 0; $i < 60; $i++) {
        $this->actingAs($this->user)
            ->getJson($this->endpoint);
    }

    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});

// Sanctum Specific Tests
it('accepts valid bearer token', function () {
    $token = $this->user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK);
});

it('rejects invalid bearer token', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer invalid-token',
    ])->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('handles expired token', function () {
    $token = $this->user->createToken('test-token', ['*'], now()->subDays(1))->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('returns empty data array when no articles exist', function () {
    Article::query()->delete();

    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(0, 'data');
});
