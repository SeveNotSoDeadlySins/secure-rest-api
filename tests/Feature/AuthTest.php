<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    //Create the database and run the migrations in each test
    use RefreshDatabase;
    use WithFaker;


    public function test_user_register(): void
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'mypassword',
            'c_password' => 'mypassword'
        ];
        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token', 'name'
            ],
            'message',
            'success'
        ]);

        $success = $response->json('success');
        $message = $response->json('message');
        $token = $response->json('data.token');
        $name = $response->json('data.name');

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'User register successfully.');
        $this->assertEquals($name, $user['name']);
        $this->assertNotNull($token);

        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email']
        ]);
    }

    public function test_user_register_error(): void
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'mypassword',
            'c_password' => 'mypassword1'
        ];
        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'data',
            'message',
            'success'
        ]);

        $message = $response->json('success');
        $errors = $response->json('message');

        $this->assertDatabaseMissing('users', [
            'name' => $user['name'],
            'email' => $user['email']
        ]);
    }

    public function test_user_login(): void
    {

    }

    public function test_user_login_error(): void
    {

    }

    public function test_user_info(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/user');
        $response->assertStatus(200);
        $responce->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ]);

        $this->assertEquals($response->json('name'), $user->name);
        $this->assertEquals($response->json('email'), $user->email);
    }
}
