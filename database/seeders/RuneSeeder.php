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
            'password' => Hash::make('securePassword123'), // Use a secure password in production
            'group_id' => 8,
            'balance' => 9999999.99, // Note: Consider using a more realistic value
            'image_url' => 'https://i.imgur.com/4Ke1v5Y.jpg',
            'github' => '',
            'url' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
