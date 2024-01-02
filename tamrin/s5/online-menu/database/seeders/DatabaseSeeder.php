<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create(['email' => 'admin@email.com']);
        Province::factory(10)->hasCities(20)->create();

        $cities = City::all('id');
        User::factory(50)
            ->has(UserAddress::factory(rand(1, 3))->sequence(fn () => ['city_id' => $cities->random()->id]), 'addresses')
            ->create();

        Product::factory(20)->create();
        Message::factory(20)->create();

        Order::factory(100)
            ->has(OrderItem::factory(rand(1, 5))
                ->sequence(fn () => ['product_id' => Product::all()->random()->id]),
                'items')
            ->sequence(fn () => [
                'user_id' => User::all()->random()->id,
                'address_id' => UserAddress::all()->random()->id,
            ])->create();
    }
}
