<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'description' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 1, 200),
            'date' => Carbon::today()->toDateString(),
        ];
    }
}
