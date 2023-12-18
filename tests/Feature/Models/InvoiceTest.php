<?php

namespace Tests\Feature\Models;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function test_successfully_show_invoices(): void
    {
        // Arrange
        $user = User::factory()->create();
        $state = array_fill(0, 10, ['user_id' => $user->id]);
        Invoice::factory()->count(10)->state(...$state)->create();

        $this->actingAs($user);

        // Act
        $response = $this->get(route('invoices.index'));

        // Assert
        $response->assertOk();
    }
}
