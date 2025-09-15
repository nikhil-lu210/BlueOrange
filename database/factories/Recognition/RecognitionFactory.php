<?php

namespace Database\Factories\Recognition;

use App\Models\User;
use App\Models\Recognition\Recognition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * Recognition Factory
 * 
 * To create a bunch of recognitions by tinker:
 * App\Models\Recognition\Recognition::factory()->count(50)->create();
 * 
 * This will create 50 recognitions at a time
 */
class RecognitionFactory extends Factory
{
    protected $model = Recognition::class;

    public function definition()
    {
        // Get available categories from config
        $categories = config('recognition.categories');
        $minMark = config('recognition.marks.min');
        $maxMark = config('recognition.marks.max');

        // Get random users for recipient and recognizer
        $users = User::where('status', 'Active')->get();
        
        if ($users->isEmpty()) {
            // Fallback if no users exist
            $recipientId = 1;
            $recognizerId = 1;
        } else {
            $recipient = $users->random();
            $recognizer = $users->where('id', '!=', $recipient->id)->random() ?? $users->random();
            $recipientId = $recipient->id;
            $recognizerId = $recognizer->id;
        }

        return [
            'user_id' => $recipientId,
            'recognizer_id' => $recognizerId,
            'category' => Arr::random($categories),
            'total_mark' => $this->faker->numberBetween($minMark, $maxMark),
            'comment' => $this->generateRecognitionComment(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }


    /**
     * Create recognitions and save them without triggering observers
     */
    public function createWithoutObservers(array $attributes = [])
    {
        // Temporarily disable observers
        Recognition::unsetEventDispatcher();
        
        try {
            $recognition = $this->create($attributes);
            return $recognition;
        } finally {
            // Re-enable observers
            Recognition::setEventDispatcher(app('events'));
        }
    }

    /**
     * Generate realistic recognition comments based on category
     */
    private function generateRecognitionComment(): string
    {
        $comments = [
            'Behavior' => [
                'Excellent professional behavior and positive attitude towards work.',
                'Always maintains a respectful and courteous demeanor with colleagues.',
                'Demonstrates outstanding workplace etiquette and professionalism.',
                'Consistently shows positive behavior that enhances team morale.',
                'Exemplary conduct that sets a great example for others.'
            ],
            'Appreciation' => [
                'Outstanding work that deserves recognition and appreciation.',
                'Thank you for your dedication and exceptional contributions.',
                'Your efforts have not gone unnoticed. Keep up the great work!',
                'We truly appreciate your commitment to excellence.',
                'Your hard work and dedication are greatly valued by the team.'
            ],
            'Leadership' => [
                'Demonstrated exceptional leadership skills in guiding the team.',
                'Showed great initiative in leading the project to success.',
                'Excellent leadership qualities that inspire and motivate others.',
                'Outstanding ability to lead by example and drive results.',
                'Proven leadership capabilities that benefit the entire organization.'
            ],
            'Loyalty' => [
                'Long-term commitment and loyalty to the organization.',
                'Dedicated service that demonstrates true loyalty to the company.',
                'Consistent loyalty and commitment over the years.',
                'Your loyalty and dedication are truly appreciated.',
                'Outstanding loyalty that contributes to organizational stability.'
            ],
            'Dedication' => [
                'Exceptional dedication to achieving project goals.',
                'Shows unwavering dedication to quality and excellence.',
                'Outstanding dedication that goes above and beyond expectations.',
                'Your dedication to the team and projects is remarkable.',
                'Consistent dedication that drives successful outcomes.'
            ],
            'Teamwork' => [
                'Excellent teamwork skills that enhance collaboration.',
                'Outstanding ability to work effectively with team members.',
                'Demonstrates exceptional teamwork and collaboration skills.',
                'Your teamwork contributes significantly to project success.',
                'Great team player who always supports colleagues.'
            ],
            'Innovation' => [
                'Brought innovative solutions that improved our processes.',
                'Creative thinking that led to breakthrough improvements.',
                'Outstanding innovation that drives organizational growth.',
                'Your innovative approach has transformed our workflow.',
                'Exceptional creativity and innovation in problem-solving.'
            ]
        ];

        $category = $this->faker->randomElement(array_keys($comments));
        return $this->faker->randomElement($comments[$category]);
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Recognition $recognition) {
            // Ensure the recognizer is not the same as the recipient
            if ($recognition->user_id === $recognition->recognizer_id) {
                $otherUsers = User::where('id', '!=', $recognition->user_id)
                    ->where('status', 'Active')
                    ->get();
                
                if ($otherUsers->isNotEmpty()) {
                    $recognition->update([
                        'recognizer_id' => $otherUsers->random()->id
                    ]);
                }
            }
        });
    }

    /**
     * Create recognitions without triggering observers (for testing/seeding)
     */
    public function withoutObservers()
    {
        return $this->state(function (array $attributes) {
            return [];
        })->afterCreating(function (Recognition $recognition) {
            // This method is for explicit use when you want to disable observers
        });
    }


    /**
     * Create recognitions for a specific user
     */
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * Create recognitions by a specific recognizer
     */
    public function byRecognizer(User $recognizer)
    {
        return $this->state(function (array $attributes) use ($recognizer) {
            return [
                'recognizer_id' => $recognizer->id,
            ];
        });
    }

    /**
     * Create recognitions for a specific category
     */
    public function forCategory(string $category)
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'category' => $category,
            ];
        });
    }

    /**
     * Create high-scoring recognitions
     */
    public function highScore()
    {
        $maxMark = config('recognition.marks.max');
        $highScoreMin = (int) ($maxMark * 0.8); // 80% of max score

        return $this->state(function (array $attributes) use ($maxMark, $highScoreMin) {
            return [
                'total_mark' => $this->faker->numberBetween($highScoreMin, $maxMark),
            ];
        });
    }

    /**
     * Create recent recognitions (last 30 days)
     */
    public function recent()
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            ];
        });
    }
}
