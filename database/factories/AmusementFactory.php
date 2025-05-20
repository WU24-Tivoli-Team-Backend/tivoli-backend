<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Amusement;
use App\Models\Group;
use App\Models\Stamp;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Amusement>
 */
class AmusementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'group_id' => fake()->randomElement(Group::pluck('id')),
            'name' => $this->faker->name(),
            'type'  => $this->faker->randomElement(['attraction', 'game']),
            'description' => $this->faker->text(200),
            'image_url' => null,
            'url' => $this->faker->url(),
            'stamp_id' => fake()->randomElement(Stamp::pluck('id')),
        ];
    }
}
