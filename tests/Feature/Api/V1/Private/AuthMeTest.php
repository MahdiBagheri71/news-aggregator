<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Private;

use App\Http\Resources\Api\V1\Private\AuthMeResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->endpoint = route('v1.private.auth-me.show');
    $this->user = User::factory()->create();
});

// Authentication Tests
it('requires authentication', function () {
    $response = $this->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('returns user data for authenticated user', function () {
    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ],
            'message',
            'status',
        ])
        ->assertJsonPath('message', __('success'))
        ->assertJsonPath('status', Response::HTTP_OK);
});

it('returns correct user data', function () {
    $response = $this->actingAs($this->user)
        ->getJson($this->endpoint);

    $response->assertJson([
        'data' => [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ],
    ]);
});

// Resource Tests
it('resource returns correct structure', function () {
    $resource = new AuthMeResource(
        $this->user,
        'success',
        Response::HTTP_OK
    );

    $response = $resource->response()->getData(true);

    expect($response)
        ->toHaveKey('data')
        ->and($response['data'])->toHaveKeys(['id', 'name', 'email'])
        ->and($response['message'])->toBe('success')
        ->and($response['status'])->toBe(Response::HTTP_OK);
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
