<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'tax_id' => (string) fake()->numberBetween(1_000_000, 10_000_000 - 1),
            'address' => fake()->streetAddress(),
            'user_id' => fake()->numberBetween(0, 1000),
        ];
    }
}
