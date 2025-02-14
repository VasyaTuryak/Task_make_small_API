<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $howManyOrdersDoYouWantAdd = 100;
        $NumbersItemsInOrder = 2;

        Order::factory($howManyOrdersDoYouWantAdd)->create()->each(function ($order) use ($NumbersItemsInOrder) {

            $total_amount=0;
            for ($i = 1; $i <= $NumbersItemsInOrder; $i++) {

                $quantity=rand(1,5);
                $price=rand(1,200);

                OrderItem::factory(1)->create([
                    'order_id' => $order->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $total_amount+=$quantity*$price;
            }

            $order->total_amount=$total_amount;
            $order->save();
        });

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
