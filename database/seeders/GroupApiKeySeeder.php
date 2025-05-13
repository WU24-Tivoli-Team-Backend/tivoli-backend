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
        // Gå igenom alla grupper i databasen
        $groups = Group::all();

        foreach ($groups as $group) {
            // Radera gamla tokens (frivilligt men rekommenderat)
            $group->tokens()->delete();

            // Skapa en ny Sanctum-token
            $token = $group->createToken('Group API Token');
            $plainTextToken = $token->plainTextToken;

            // Visa i terminal
            echo "Group ID {$group->id} API Key: {$plainTextToken}\n";
        }
    }
}
