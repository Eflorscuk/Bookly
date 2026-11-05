<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->randomElement(['Haircut', 'Manicure', 'Consultation', 'Massage']),
            'description' => fake()->optional()->sentence(),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'price_cents' => fake()->numberBetween(2000, 30000),
            'is_active' => true,
        ];
    }
}
