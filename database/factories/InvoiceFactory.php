<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'draft' => fake()->boolean(),
            'paid_at' => fake()->date(),

            'series' => fake()->lexify('??'),
            // 'number' => fake()->numberBetween(0, 100),

            'subtotal' => fake()->numberBetween(100, 1000),
            'tax' => fake()->numberBetween(0, 100),

            'client_id' => fake()->numberBetween(100, 1000),
            'client_name' => fake()->name(),
            'client_tax_id' => fake()->bothify('??##########'),
            'client_address' => fake()->streetAddress(),

            'vendor_name' => fake()->name(),
            'vendor_tax_id' => fake()->bothify('??##########'),
            'vendor_address' => fake()->streetAddress(),

            'user_id' => fake()->numberBetween(100, 1000),
        ];
    }
}
