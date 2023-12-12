<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_successfully_create_a_product(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->make();

        $response = $this->post(route('products.store'), $product->toArray());

        $response->assertStatus(302);
        $response->assertRedirectToRoute('products.index');
    }

    public function test_throw_when_creating_a_product_with_incomplete_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Product::factory()->create(['user_id' => $user->id]);
        $new_product_data = Product::factory()->make()->makeHidden('name');

        $response = $this->post(route('products.store'), $new_product_data->toArray());

        $response->assertSessionHasErrors(['name']);
        // $response->assertStatus(302);
        // $response->assertRedirectToRoute('products.index');
        // var_dump($response->baseResponse->getContent());
        // var_dump($response->baseResponse->status());
    }


    public function test_successfully_update_a_product(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create(['user_id' => $user->id]);
        $new_product_data = Product::factory()->make();

        $response = $this->put(route('products.update', $product->id), $new_product_data->toArray());


        $response->assertStatus(302);
        $response->assertRedirectToRoute('products.index');
    }

    public function test_throw_when_updating_a_product_with_incomplete_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create(['user_id' => $user->id]);
        $new_product_data = Product::factory()->make()->makeHidden('name');

        $response = $this->put(route('products.update', $product->id), $new_product_data->toArray());

        $response->assertSessionHasErrors(['name']);
    }

    public function test_throw_when_updating_a_product_as_an_unauthorized_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $unauthorized_user = User::factory()->create();
        $this->actingAs($unauthorized_user);
        $new_product_data = Product::factory()->make();

        $response = $this->put(route('products.update', $product->id), $new_product_data->toArray());

        $response->assertForbidden();
    }
}
