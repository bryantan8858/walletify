<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'type' => $this->faker->randomElement(['General', 'Saving Account', 'Credit/Debit Card', 'Cash', 'Insurance', 'Loan', 'Investment']),
            'name' => $this->faker->name,
        ];
    }
}
