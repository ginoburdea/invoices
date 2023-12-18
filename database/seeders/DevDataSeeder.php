<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', '=', 'email@example.com')->first();
        if ($user) {
            return;
        }
        $user = User::factory()->create([
            'email' => 'email@example.com',
            'password' => 'email@example.com'
        ]);

        $state = array_fill(0, 5, ['user_id' => $user->id]);
        Product::factory()->count(5)->state(...$state)->create();
        $clients = Client::factory()->count(5)->state(...$state)->create();

        foreach ($clients as $index => $client) {
            $state[$index]['client_id'] = $client->id;
        }
        Invoice::factory()->count(5)->state(...$state)->create();
    }
}
