<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => fake()->text(),
            'sku' => fake()->text(),
            'price' => fake()->numberBetween(0, 1000),
            'tax_percentage' => fake()->numberBetween(0, 100),
            'user_id' => fake()->numberBetween(0, 1000),
        ];
    }
}
