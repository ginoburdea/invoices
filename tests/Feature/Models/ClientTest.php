<?php

namespace Tests\Feature\Models;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_successfully_show_clients(): void
    {
        // Arrange
        $user = User::factory()->create();
        $clients_state = array_fill(0, 10, ['user_id' => $user->id]);
        Client::factory()->count(10)->state(...$clients_state)->create();
        $this->actingAs($user);

        // Act
        $response = $this->get(route('clients.index'));

        // Assert
        $response->assertOk();
    }

    public function test_successfully_create_a_client(): void
    {
        // Arrange
        $user = User::factory()->create();
        $client_data = Client::factory()->make();

        $this->actingAs($user);

        // Act
        $response = $this->post(route('clients.store'), $client_data->toArray());

        // Assert
        $response->assertRedirectToRoute('clients.index');

        $clients_count = Client::where('user_id', $user->id)->count();
        $this->assertEquals($clients_count, 1);
    }

    public function test_successfully_update_a_client(): void
    {
        // Arrange
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $updated_client_data = Client::factory()->make(['user_id' => $user->id]);

        $this->actingAs($user);

        // Act
        $response = $this->put(route('clients.update', $client->id), $updated_client_data->toArray());

        // Assert
        $response->assertRedirectToRoute('clients.index');

        $client->refresh();
        $this->assertEquals($client->name, $updated_client_data->name);
    }

    public function test_successfully_delete_a_client(): void
    {
        // Arrange
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Act
        $response = $this->delete(route('clients.destroy', $client->id));

        // Assert
        $response->assertRedirectToRoute('clients.index');
        $this->assertThrows(fn() => $client->refresh());
    }

    public function test_throw_when_deleting_a_client_as_an_unauthorized_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        $product = Client::factory()->create(['user_id' => $user->id]);

        $unauthorized_user = User::factory()->create();
        $this->actingAs($unauthorized_user);

        // Act
        $response = $this->delete(route('clients.destroy', $product->id));

        // Assert
        $response->assertForbidden();
    }
}
