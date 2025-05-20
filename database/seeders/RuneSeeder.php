<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Rune Pandadottir',
            'email' => 'rune@yrgobanken.vip',
            'password' => Hash::make('password'), // Use a secure password in production
            'group_id' => 8,
            'balance' => 9999999.99, // Note: Consider using a more realistic value
            'image_url' => null,
            'github' => '',
            'url' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rune = DB::table('users')->where('email', 'rune@yrgobanken.vip')->first();

        // Output as JSON
        $this->command->info(json_encode($rune, JSON_PRETTY_PRINT));
    }
}
