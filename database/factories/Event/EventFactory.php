<?php

namespace Database\Factories\Event;

use App\Models\Event\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event\Event>
 */
class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');
        
        $isAllDay = $this->faker->boolean(20);
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'start_time' => $isAllDay ? null : $this->faker->time('H:i'),
            'end_time' => $isAllDay ? null : $this->faker->time('H:i'),
            'location' => $this->faker->optional(0.7)->city(),
            'event_type' => $this->faker->randomElement(['meeting', 'training', 'celebration', 'conference', 'workshop', 'other']),
            'status' => $this->faker->randomElement(['draft', 'published', 'cancelled', 'completed']),
            'organizer_id' => User::factory(),
            'is_all_day' => $isAllDay,
            'color' => $this->faker->randomElement(['#3788d8', '#dc3545', '#28a745', '#ffc107', '#6f42c1', '#fd7e14']),
            'max_participants' => $this->faker->optional(0.6)->numberBetween(5, 100),
            'current_participants' => 0,
            'is_public' => $this->faker->boolean(80),
            'reminder_before' => $this->faker->optional(0.7)->numberBetween(15, 1440),
            'reminder_unit' => $this->faker->randomElement(['minutes', 'hours', 'days']),
        ];
    }

    /**
     * Indicate that the event is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the event is all day.
     */
    public function allDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_all_day' => true,
            'start_time' => null,
            'end_time' => null,
        ]);
    }

    /**
     * Indicate that the event is a meeting.
     */
    public function meeting(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'meeting',
        ]);
    }

    /**
     * Indicate that the event is a training.
     */
    public function training(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'training',
        ]);
    }

    /**
     * Indicate that the event is a celebration.
     */
    public function celebration(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'celebration',
        ]);
    }
}
