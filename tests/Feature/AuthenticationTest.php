<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    public function testUserCannotLoginWithIncorrectCredentials() {
        $user = User::factory()->create();
        $response = $this->postJson('api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'not_correct_password',
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanRegisterWithCorrectCredentials()
    {
        $user = [
            'name' => fake()->name,
            'email' => fake()->email
        ];

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'access_token',
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => $user['name'],
            'email' => $user['email'],
        ]);
    }

    public function testUserCannotRegisterWithIncorrectCredentials() {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
        ]);
    }
}
