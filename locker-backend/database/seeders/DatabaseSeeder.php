<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemLoan;
use App\Models\User;
use App\Services\FakeLockerService;
use Database\Factories\ItemFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('string'),
        ]);

        $locker_list = (new FakeLockerService)->getLockerList();

        ItemFactory::new()->count(count($locker_list))->create([
            'locker_id' => Arr::random($locker_list)->id,
        ]);

        // Erstelle einige Benutzer
        $users = User::factory()->count(5)->create();

        // Erstelle einige Items
        $items = Item::factory()->count(10)->create();

        // Erstelle einige aktive Ausleihen
        foreach ($items->take(5) as $item) {
            ItemLoan::factory()->create([
                'item_id' => $item->id,
                'user_id' => $users->random()->id,
            ]);
        }

        // Erstelle einige zurückgegebene Ausleihen
        foreach ($items->skip(5) as $item) {
            ItemLoan::factory()->returned()->create([
                'item_id' => $item->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
