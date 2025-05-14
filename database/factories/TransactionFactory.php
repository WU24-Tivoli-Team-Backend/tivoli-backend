<?php

namespace Database\Factories;

use App\Models\Amusement;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::whereNotNull('group_id')->inRandomOrder()->first();
        
        return [
            'amusement_id' => fake()->randomElement(Amusement::pluck('id')),
            'user_id' => $user->id, // Get the user_id from the group
            'group_id' => $user->group_id,
            'stake_amount' => fake()->numberBetween(1, 5),
            'payout_amount' => fake()->numberBetween(1, 5),
            'stamp_id' => fake()->randomElement(Amusement::pluck('stamp_id')),
        ];
    }
}
