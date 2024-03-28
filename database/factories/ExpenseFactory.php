<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'amount' => $this->faker->numberBetween(1000, 10000),
            'category' => $this->faker->randomElement(['Utilities', 'Rent', 'Food', 'Transport']),
            'user_id' => function () {
                User::factory()->create()->id;
            },
        ];
    }
}
