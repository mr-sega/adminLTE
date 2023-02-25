<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Customer Security Engineer', 'Net Developer', 'PHP Developer', 'Software Engineer',
                'Front-End Web Developer', 'Web Designer', 'Full-Stack Developer', 'Web Analyst', 'UX/UI Developer',
                'Back-End Web Developer'
            ]),
        ];
    }
}
