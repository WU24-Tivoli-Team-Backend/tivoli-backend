<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Group;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {



        $firstName = $this->faker->firstName;
        
        return [
            'name' => $firstName,
            'email' => "$firstName@yrgobanken.vip",
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'group_id' => function () {
                // This callback runs when the factory is actually creating the model
                // ensuring that Group::pluck() is called at the right time
                $groupIds = Group::pluck('id')->toArray();
                
                // If there are no groups yet, create one
                if (empty($groupIds)) {
                    return Group::factory()->create()->id;
                }
                
                return $this->faker->randomElement($groupIds);
            },
            'balance' => $this->faker->numberBetween(0, 1000),
            'image_url' => '',
            'github' => '',
            'url' => '',
            'remember_token' => Str::random(10),
        ];
    
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
