<?php

namespace Database\Factories\IncomeExpense;

use App\Models\User;
use App\Models\IncomeExpense\Expense;
use App\Models\IncomeExpense\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $this->faker->randomFloat(2, 10, 1000);

        return [
            'creator_id' => User::inRandomOrder()->first(),
            'category_id' => IncomeExpenseCategory::inRandomOrder()->first(),
            'title' => $this->faker->word,
            'date' => $this->faker->dateTimeThisYear(),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price,
            'description' => $this->faker->sentence,
        ];
    }
}
