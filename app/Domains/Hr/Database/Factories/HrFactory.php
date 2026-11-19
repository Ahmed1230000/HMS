<?php

namespace App\Domains\Hr\Database\Factories;

use App\Models\Hr;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrFactory extends Factory
{
    protected $model = Hr::class;

    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->numerify('EMP-#####'),

            'name' => fake()->name(),

            'phone' => fake()->numerify('01#########'),

            'email' => fake()->unique()->safeEmail(),

            'gender' => fake()->randomElement([
                'male',
                'female',
            ]),

            'date_of_birth' => fake()
                ->dateTimeBetween('-60 years', '-22 years')
                ->format('Y-m-d'),

            'national_id' => fake()->unique()->numerify('##############'),

            'address' => fake()->address(),

            'hire_date' => fake()
                ->dateTimeBetween('-10 years', 'now')
                ->format('Y-m-d'),

            'job_title' => fake()->randomElement([
                'HR Manager',
                'HR Specialist',
                'HR Coordinator',
                'Recruitment Specialist',
                'HR Assistant',
            ]),

            'is_active' => fake()->boolean(90),

            'user_id' => null,

            'created_by' => null,
        ];
    }
}
