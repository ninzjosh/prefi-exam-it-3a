<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $sexOptions = ['MALE', 'FEMALE'];
        $courseOptions = ['BSIT', 'BSCS', 'BSHM'];

        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'birthdate' => $this->faker->date('Y-m-d'),
            'sex' => $sexOptions[array_rand($sexOptions)],
            'address' => $this->faker->address,
            'year' => $this->faker->numberBetween(1, 4),
            'course' => $courseOptions[array_rand($courseOptions)],
            'section' => array_rand(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D']),
        ];
    }
}
