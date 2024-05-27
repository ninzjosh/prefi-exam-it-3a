<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = [
            'prelims' => $this->faker->randomFloat(2, 0, 4),
            'midterms' => $this->faker->randomFloat(2, 0, 4),
            'pre_finals' => $this->faker->randomFloat(2, 0, 4),
            'finals' => $this->faker->randomFloat(2, 0, 4),
        ];
        
        $average_grade = array_sum($grades) / count($grades);
        $remarks = $average_grade >= 3.0 ? 'PASSED' : 'FAILED';

        return [
            'subject_code' => strtoupper($this->faker->bothify('???###')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'instructor' => $this->faker->name,
            'schedule' => $this->faker->dayOfWeek . ' ' . $this->faker->time,
            'grades' => json_encode($grades),
            'average_grade' => $average_grade,
            'remarks' => $remarks,
            'date_taken' => $this->faker->date('Y-m-d'),
        ];
    }
}
