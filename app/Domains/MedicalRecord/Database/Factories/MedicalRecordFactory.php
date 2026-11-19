<?php

namespace App\Domains\MedicalRecord\Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Domains\MedicalRecord\Enums\MedicalRecordStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),

            'chief_complaint' => fake()->optional()->sentence(),

            'diagnosis' => fake()->optional()->sentence(),

            'clinical_notes' => fake()->optional()->paragraph(),

            'treatment_plan' => fake()->optional()->paragraph(),

            'status' => fake()->randomElement(
                array_column(MedicalRecordStatusEnum::cases(), 'value')
            ),

            'created_by' => null,
        ];
    }
}
