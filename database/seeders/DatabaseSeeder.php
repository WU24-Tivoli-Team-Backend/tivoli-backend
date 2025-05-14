<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(GroupSeeder::class);

        User::factory()->count(10)->create();

        User::factory()->create([
            'name' => 'Rune Pandadottir',
            'email' => 'rune@yrgobanken.vip',
            'password' => bcrypt('password'),
            'group_id' => 1,
            'balance' => 25,
            'image_url' => 'https://i.imgur.com/4Ke1v5Y.jpg',
            'github' => '',
            'url' => '',
        ]);

        $this->call(StampSeeder::class);

        $this->call(AmusementSeeder::class);

        $this->call(TransactionSeeder::class);

        $this->call([
            GroupApiKeySeeder::class,
        ]);
    }
}
