<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_get_their_profile(){

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/profile');

        $response->assertStatus(200)
            /* ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2) */
            ->assertJsonFragment(['name' => $user->name]);

    }

    public function test_user_can_update_name_and_email(){

        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/profile',[
            'name' => 'kiko',
            'email' => 'correo@correo.com'
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'kiko']);

        $this->assertDatabaseHas('users', [
            'name' => 'kiko',
            'email' => 'correo@correo.com'
        ]);

    }

    public function test_user_can_update_password(){

        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/password',[
            'current_password' => 'password',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(202);

    }

}
