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
}
