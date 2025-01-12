<?php

use App\Models\Chirp;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns unauthorized when get list of chirps', function () {
    $this->getJson('/api/chirps')
        ->assertUnauthorized();
});

it('returns success when get list of chirps', function () {
    Sanctum::actingAs(User::factory()->create());

    $count = fake()->randomDigitNot(0);

    Chirp::factory()
        ->count($count)
        ->create();

    $this->getJson('/api/chirps')
        ->assertOk()
        ->assertJsonCount($count, 'data')
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'message',
                    'created_at',
                ],
            ],
        ]);

    $this->assertDatabaseCount('chirps', $count);
});
