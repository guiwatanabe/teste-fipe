<?php

namespace Tests\Feature;

use App\Models\User;

test('api requires authentication', function () {
    $response = $this->getJson('/api/marcas');
    $response->assertStatus(401);
});

test('api authentication generates token', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/auth', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

test('api user cannot authenticate with wrong credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/auth', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['username' => [trans('auth.failed')]]]);
});

test('api cannot be accessed with invalid token', function () {
    $response = $this->getJson('/api/marcas', ['Authorization' => 'Bearer: 1|Invalid']);

    $response->assertStatus(401);
});
