<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animals = ['panda', 'orca', 'raven', 'blobfish', 'pallas cat'];
        $premiumAttributes = ['silver', 'gold', 'platinum'];
        
        $now = Carbon::now();
        
        $data = [];
        
        // Create a record for each animal with no premium attribute
        foreach ($animals as $animal) {
            $data[] = [
                'animal' => $animal,
                'premium_attribute' => null, // No premium attribute
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        // Create a record for each animal with each premium attribute
        foreach ($animals as $animal) {
            foreach ($premiumAttributes as $attribute) {
                $data[] = [
                    'animal' => $animal,
                    'premium_attribute' => $attribute,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        // Insert all records into the database
        // Note: Replace 'table_name' with your actual table name
        DB::table('stamps')->insert($data);
    }
}
