<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Amusement;

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

            'group_id' => 1,
            'name' => $this->faker->name(),
            'type'  => $this->faker->randomElement(['attraction', 'game']),
            'description' => $this->faker->text(200),
            'image_url' => $this->faker->imageUrl(640, 480, 'amusement'),
            'url' => $this->faker->url(),
            'stamp_id' => null,
        ];
    }
}
