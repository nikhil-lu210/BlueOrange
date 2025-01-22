<?php

namespace Database\Factories\IncomeExpense;

use App\Models\IncomeExpense\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomeExpense\IncomeExpenseCategory>
 */
class IncomeExpenseCategoryFactory extends Factory
{
    protected $model = IncomeExpenseCategory::class;

    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(2, true)),
            'description' => $this->faker->sentence,
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
