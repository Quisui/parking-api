<?php

namespace Tests\Feature;

use App\Models\Parking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanGetTheirOwnVehicles()
    {
        $peter = User::factory()->create();
        $peterVehicle = Vehicle::factory()->create([
            'user_id' => $peter->id,
        ]);

        $john = User::factory()->create();
        $johnVehicle = Vehicle::factory()->create([
            'user_id' => $john->id,
        ]);

        $this->getJson('/api/v1/vehicles')
            ->assertStatus(401);

        $this->assertModelExists($john);
        $this->actingAs($peter)->getJson('/api/v1/vehicles')
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.plate_number', $peterVehicle->plate_number)
            ->assertJsonMissing($johnVehicle->toArray());
    }

    public function testUserCanCreateVehicle()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/v1/vehicles', [
            'plate_number' => '',
        ])
            ->assertStatus(422)
            ->assertInvalid('plate_number');

        $this->actingAs($user)->postJson('/api/v1/vehicles', [
            'plate_number' => 'AAA111',
        ])
            ->assertCreated()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['data' => ['0' => 'plate_number']])
            ->assertJsonPath('data.plate_number', 'AAA111');

        $this->assertDatabaseHas('vehicles', ['plate_number' => 'AAA111']);
    }

    public function testUserCanUpdateTheirVehicle()
    {
        $user = User::factory()->create();
        $vehicleUser = Vehicle::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->putJson('/api/v1/vehicles/' . $vehicleUser->id, [
            'plate_number' => '',
        ])
            ->assertInvalid('plate_number');

        $this->actingAs($user)->putJson('/api/v1/vehicles/' . $vehicleUser->id, [
            'plate_number' => 'AAA222',
        ])
            ->assertStatus(202)
            ->assertJsonStructure(['id', 'plate_number'])
            ->assertJsonPath('plate_number', 'AAA222');
    }

    /* Means that has parking pending to stop before delete */
    public function testUserCannotDeleteHisVehicle()
    {
        $user = User::factory()->create();
        $vehicleUser = Vehicle::factory()->create([
            'user_id' => $user->id,
        ]);
        $zone = Zone::find(1);
        Parking::create([
            'vehicle_id' => $vehicleUser->id,
            'zone_id' => $zone->id,
            'user_id' => $user->id,
            'start_time' => now()->toDateTimeString(),
            'stop_time' => null,
            'total_price' => 0,
        ]);

        $this->actingAs($user)->deleteJson('/api/v1/vehicles/' . $vehicleUser->id)
            ->assertJsonFragment(['errors' => [
                'general' => [
                    'Can\'t remove vehicle with active parkings. Stop active parking.'
                ]
            ]]);
    }

    public function testUserCanDeleteHisVehicle()
    {
        $user = User::factory()->create();
        $vehicleUser = Vehicle::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->deleteJson('/api/v1/vehicles/' . $vehicleUser->id)
            ->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseMissing('vehicles', $vehicleUser->toArray())
            ->assertDatabaseCount('vehicles', 0);
    }
}
