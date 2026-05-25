<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_and_me(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'user', 'token']);

        $login = $this->postJson('/api/auth/login', [
            'email' => 'tester@example.com',
            'password' => 'secret1234',
        ]);

        $login->assertStatus(200)->assertJsonStructure(['message', 'user', 'token']);

        $token = $login->json('token');

        $me = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/auth/me');

        $me->assertStatus(200)->assertJsonPath('user.email', 'tester@example.com');
    }
}
