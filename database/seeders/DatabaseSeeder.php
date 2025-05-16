<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(GroupSeeder::class);
        $this->call(GroupApiKeySeeder::class);

        User::factory()->count(10)->create();

        $this->call(RuneSeeder::class);

        $this->call(StampSeeder::class);

        $this->call(AmusementSeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
