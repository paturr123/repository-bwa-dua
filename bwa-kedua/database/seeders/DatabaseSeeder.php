<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
            // User::factory(10)->withPersonalTeam()->create();

            $this->call([
                UsersTableSeeder::class,
                DetailUserTableSeeder::class,
                OrderStatusTableSeeder::class,                
            ]);

    }
}
