<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemLoan;
use App\Models\Locker;
use App\Models\User;
use Database\Factories\ItemFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('string'),
        ]);

        $admin->makeAdmin();

        $locker_list = Locker::factory()->count(5)->create();

        ItemFactory::new()->count(count($locker_list))->create([
            'locker_id' => $locker_list->random()->id,
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
