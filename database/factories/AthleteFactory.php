<?php

namespace Database\Factories;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Athlete>
 */
class AthleteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'identity_document' => $this->faker->unique()->numberBetween(1000000, 7000000),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'club' => $this->faker->company(),
            'carnet_number' => $this->faker->optional()->numberBetween(100, 1000),
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-15 years')->format('Y-m-d'),
            'city' => $this->faker->city(),
            'country' => 'Paraguay',
        ];
    }
}
