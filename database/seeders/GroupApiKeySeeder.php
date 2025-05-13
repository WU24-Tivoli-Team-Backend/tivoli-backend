<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKeys = config('api-keys.groups');
        
        foreach ($apiKeys as $groupId => $apiKey) {
            // Find or create the group
            $group = Group::firstOrCreate(
                ['id' => $groupId],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            // Update the group with the API key
            $group->update(['api_key' => $apiKey]);
            
            // Output the key for reference
            $this->command->info("Group ID {$group->id} API Key: {$apiKey}");
        }
    }
}
