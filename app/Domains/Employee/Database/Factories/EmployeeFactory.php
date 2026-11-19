<?php

namespace App\Domains\Employee\Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => null,

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
                'Doctor',
                'Receptionist',
                'Accountant',
                'Nurse',
                'Pharmacist',
                'IT Specialist',
                'HR Specialist',
            ]),

            'is_active' => fake()->boolean(90),

            'created_by' => null,
        ];
    }
}
