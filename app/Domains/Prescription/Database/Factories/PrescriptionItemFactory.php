<?php

namespace App\Domains\PrescriptionItem\Database\Factories;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Domains\Prescription\Enums\PrescriptionItemStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionItemFactory extends Factory
{
    protected $model = PrescriptionItem::class;

    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::factory(),

            'medication_name' => fake()->randomElement([
                'Paracetamol',
                'Amoxicillin',
                'Ibuprofen',
                'Omeprazole',
                'Cetirizine',
                'Metformin',
            ]),

            'dosage' => fake()->randomElement([
                '500 mg',
                '250 mg',
                '100 mg',
                '1 tablet',
                '2 tablets',
            ]),

            'frequency' => fake()->randomElement([
                'Once daily',
                'Twice daily',
                'Three times daily',
                'Every 8 hours',
                'As needed',
            ]),

            'duration' => fake()->randomElement([
                '3 days',
                '5 days',
                '7 days',
                '10 days',
                '14 days',
            ]),

            'instructions' => fake()->optional()->randomElement([
                'Take after meals.',
                'Take before meals.',
                'Drink plenty of water.',
                'Avoid taking on an empty stomach.',
            ]),

            'status' => PrescriptionItemStatusEnum::ACTIVE->value,
        ];
    }
}
