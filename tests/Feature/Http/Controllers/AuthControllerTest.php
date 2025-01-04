<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('return error when login with empty email and password', function () {
    $this->postJson('/api/login')
        ->assertUnprocessable()
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'password',
            ],
        ]);
});

it('return error when login with empty email', function () {
    $data = [
        'password' => 'password',
    ];

    $this->postJson('/api/login', [])
        ->assertUnprocessable()
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
});

it('return error when login with not exist email', function () {
    $data = [
        'email' => fake()->email(),
        'password' => 'password',
    ];

    $this->postJson('/api/login', $data)
        ->assertUnprocessable()
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
});

it('return error when login with false password', function () {
    $user = User::factory()->create();
    $data = [
        'email' => $user->email,
        'password' => '123456',
    ];

    $this->postJson('/api/login', $data)
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'login failed.',
        ]);
});

it('return success when login', function () {
    $user = User::factory()->create();

    $data = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $this->postJson('/api/login', $data)
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'token',
        ]);
});

it('return unauthorized when logout', function (string $method) {
    $this->json($method, '/api/logout')
        ->assertUnauthorized();
})->with(['get', 'post']);

it('return success when logout', function (string $method) {
    Sanctum::actingAs(
        User::factory()->create()
    );

    $this->json($method, '/api/logout')
        ->assertOk();
})->with(['get', 'post']);
