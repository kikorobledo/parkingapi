<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(){

        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(201);

    }

    public function test_user_cannot_login_with_incorrect_credentials(){

        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password1'
        ]);

        $response->assertStatus(422);

    }

    public function test_user_can_register_with_correct_credentials(){

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'kiko',
            'email' => 'correo@correo.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'access_token'
            ]);

        $this->assertDatabaseHas('users',[
            'name' => 'kiko',
            'email' => 'correo@correo.com'
        ]);

    }

    public function test_user_cannot_register_with_icorrect_credentials(){

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'kiko',
            'email' => 'correo@correo.com',
            'password' => 'password',
            'password_confirmation' => 'password1'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users',[
            'name' => 'kiko',
            'email' => 'correo@correo.com'
        ]);

    }

}
