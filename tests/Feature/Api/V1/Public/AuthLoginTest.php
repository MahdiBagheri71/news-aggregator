<?php

declare(strict_types=1);

use App\Http\Resources\Api\V1\Public\Auth\AuthLoginResource;
use App\Models\User;
use App\Services\Api\Actions\AuthLoginAction;
use App\Services\Api\DTO\AuthLoginDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->endpoint = route('v1.public.auth-login.show');
    $this->email = Str::random(10).'@example.com';
    $this->password = 'password123';

    $this->user = User::factory()->create([
        'email' => $this->email,
        'password' => Hash::make($this->password),
    ]);
});

// Request Validation Tests
it('login request validates required fields', function () {
    $response = $this->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('login request validates email format', function () {
    $response = $this->getJson($this->endpoint, [
        'email' => 'invalid-email',
        'password' => 'password123',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

it('login request validates password minimum length', function () {
    $response = $this->getJson($this->endpoint, [
        'email' => 'test@example.com',
        'password' => '123',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['password']);
});

// Action Tests
it('login action authenticates valid credentials', function () {
    $action = new AuthLoginAction;
    $dto = AuthLoginDTO::fromArray([
        'email' => $this->email,
        'password' => $this->password,
    ]);

    $result = $action->handle($dto);

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($this->user->id)
        ->and($result->email)->toBe($this->email);
});

it('login action successfully authenticates user with valid credentials', function () {
    $action = new AuthLoginAction;
    $dto = AuthLoginDTO::fromArray([
        'email' => $this->email,
        'password' => $this->password,
    ]);

    $result = $action->handle($dto);

    expect($result)
        ->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($this->user->id)
        ->and($result->email)->toBe($this->email)
        ->and($result->token)->not->toBeNull()
        ->and($result->token)->toBeString();
});

it('login action throws model not found exception for non-existent user', function () {
    $action = new AuthLoginAction;
    $dto = AuthLoginDTO::fromArray([
        'email' => 'nonexistent@example.com',
        'password' => $this->password,
    ]);

    expect(fn () => $action->handle($dto))
        ->toThrow(ModelNotFoundException::class);
});

it('login action aborts with unauthorized for invalid password', function () {
    $action = new AuthLoginAction;
    $dto = AuthLoginDTO::fromArray([
        'email' => $this->email,
        'password' => 'wrong-password',
    ]);

    expect(fn () => $action->handle($dto))
        ->toThrow(new HttpException(\Illuminate\Http\Response::HTTP_UNAUTHORIZED, __('Invalid credentials.'), null, []));
});

it('successful login returns token in response', function () {

    $response = $this->getJson(
        url()->query($this->endpoint, [
            'email' => $this->email,
            'password' => $this->password,
        ])
    );

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'token',
            ],
            'message',
            'status',
        ])
        ->assertJsonPath('message', __('Login successful.'))
        ->assertJsonPath('status', Response::HTTP_OK);

    expect($response->json('data.token'))->not->toBeNull();
});

it('login with non-existent user returns 404', function () {
    $response = $this->getJson(url()->query($this->endpoint, [
        'email' => 'nonexistent@example.com',
        'password' => $this->password,
    ]));

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('login with wrong password returns 401', function () {
    $response = $this->getJson(url()->query($this->endpoint, [
        'email' => $this->email,
        'password' => 'wrong-password',
    ]));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonPath('message', __('Invalid credentials.'));
});

it('login resource returns correct structure', function () {
    $this->user->token = 'test-token';
    $resource = new AuthLoginResource(
        $this->user,
        'Login successful.',
        Response::HTTP_OK
    );

    $response = $resource->response()->getData(true);

    expect($response)
        ->toHaveKey('data')
        ->and($response['data'])->toHaveKeys(['id', 'name', 'token'])
        ->and($response['message'])->toBe('Login successful.')
        ->and($response['status'])->toBe(Response::HTTP_OK);
});
