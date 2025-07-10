<?php

namespace Database\Factories\DailyWorkUpdate;

use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyWorkUpdate\DailyWorkUpdate>
 */
class DailyWorkUpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailyWorkUpdate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'team_leader_id' => User::factory(),
            'date' => $this->faker->date(),
            'work_update' => $this->faker->paragraph(3),
            'progress' => $this->faker->numberBetween(0, 100),
            'note' => $this->faker->optional()->paragraph(),
            'rating' => null,
            'comment' => null,
        ];
    }

    /**
     * Indicate that the daily work update has been rated.
     */
    public function rated(int $rating = null): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating ?? $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional()->paragraph(),
        ]);
    }

    /**
     * Indicate that the daily work update is unrated.
     */
    public function unrated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => null,
            'comment' => null,
        ]);
    }
}
