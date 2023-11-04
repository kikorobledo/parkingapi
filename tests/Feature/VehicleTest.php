<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_get_their_own_vehicle(){

        $kiko = User::factory()->create();

        $vehicleForKiko = Vehicle::factory()->create([
            'user_id' => $kiko->id
        ]);

        $rafa = User::factory()->create();

        $vehicleForRafa =  Vehicle::factory()->create([
            'user_id' => $rafa->id
        ]);

        $response = $this->actingAs($kiko)->getJson('/api/v1/vehicles');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data'])
            ->assertJsonPath('data.0.plate_number', $vehicleForKiko->plate_number)
            ->assertJsonMissing($vehicleForRafa->toArray());

    }

    public function test_user_can_create_own_vehicle(){

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/vehicles',[
            'plate_number' => '123ABC',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2,'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plate_number'],
            ])
            ->assertJsonPath('data.plate_number', '123ABC');

        $this->assertDatabaseHas('vehicles', ['plate_number' => '123ABC']);


    }

    public function test_user_can_update_own_vehicle(){

        $user = User::factory()->create();

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson('/api/v1/vehicles/' . $vehicle->id, [
            'plate_number' => '123ABC'
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['plate_number'])
            ->assertJsonPath('plate_number', '123ABC');

        $this->assertDatabaseHas('vehicles', ['plate_number' => '123ABC']);

    }

    public function test_user_can_delete_own_vehicle(){

        $user = User::factory()->create();

        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/v1/vehicles/' . $vehicle->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('vehicles', $vehicle->toArray());

    }

}
