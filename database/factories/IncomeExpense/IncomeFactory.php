<?php

namespace Database\Factories\IncomeExpense;

use App\Models\User;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\IncomeExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition()
    {
        return [
            'creator_id' => User::inRandomOrder()->first(),
            'category_id' => IncomeExpenseCategory::inRandomOrder()->first(),
            'source' => $this->faker->word,
            'date' => $this->faker->dateTimeThisYear(),
            'total' => $this->faker->numberBetween(500, 10000),
            'description' => $this->faker->sentence,
        ];
    }
}
