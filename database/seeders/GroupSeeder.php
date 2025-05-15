<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use Illuminate\Support\Str; // @todo: temp - remove when real API keys are available

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $predefinedGroups = [
            [
                //group 1
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 2
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 3
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 4
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 5
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 6
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 7
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                //group 8
                'api_key' => 'failed_static_api_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($predefinedGroups as $group){
            Group::create($group);
        }
    }
}
