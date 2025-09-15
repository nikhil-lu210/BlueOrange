<?php

namespace Database\Seeders\Recognition;

use App\Models\User;
use App\Models\Recognition\Recognition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecognitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if users exist before creating recognitions
        $userCount = User::where('status', 'Active')->count();
        
        if ($userCount < 2) {
            $this->command->warn('Not enough active users found. Please run UsersTableSeeder first.');
            return;
        }

        $this->command->info('Creating recognition data...');

        // Create general recognitions
        $this->createGeneralRecognitions();

        // Create category-specific recognitions
        $this->createCategorySpecificRecognitions();

        // Create recent recognitions for better testing
        $this->createRecentRecognitions();

        // Create high-scoring recognitions
        $this->createHighScoringRecognitions();

        $this->command->info('Recognition data created successfully!');
    }

    /**
     * Create general recognitions
     */
    private function createGeneralRecognitions(): void
    {
        $this->command->info('Creating general recognitions...');
        
        // Disable observers to prevent email sending during seeding
        Recognition::unsetEventDispatcher();
        
        try {
            Recognition::factory()
                ->count(100)
                ->create();
        } finally {
            // Re-enable observers
            Recognition::setEventDispatcher(app('events'));
        }
    }

    /**
     * Create category-specific recognitions
     */
    private function createCategorySpecificRecognitions(): void
    {
        $this->command->info('Creating category-specific recognitions...');
        
        // Disable observers to prevent email sending during seeding
        Recognition::unsetEventDispatcher();
        
        try {
            $categories = config('recognition.categories');
            
            foreach ($categories as $category) {
                Recognition::factory()
                    ->forCategory($category)
                    ->count(15)
                    ->create();
            }
        } finally {
            // Re-enable observers
            Recognition::setEventDispatcher(app('events'));
        }
    }

    /**
     * Create recent recognitions (last 30 days)
     */
    private function createRecentRecognitions(): void
    {
        $this->command->info('Creating recent recognitions...');
        
        // Disable observers to prevent email sending during seeding
        Recognition::unsetEventDispatcher();
        
        try {
            Recognition::factory()
                ->recent()
                ->count(50)
                ->create();
        } finally {
            // Re-enable observers
            Recognition::setEventDispatcher(app('events'));
        }
    }

    /**
     * Create high-scoring recognitions
     */
    private function createHighScoringRecognitions(): void
    {
        $this->command->info('Creating high-scoring recognitions...');
        
        // Disable observers to prevent email sending during seeding
        Recognition::unsetEventDispatcher();
        
        try {
            Recognition::factory()
                ->highScore()
                ->count(30)
                ->create();
        } finally {
            // Re-enable observers
            Recognition::setEventDispatcher(app('events'));
        }
    }

    /**
     * Create recognitions for specific users (for testing)
     */
    private function createUserSpecificRecognitions(): void
    {
        $this->command->info('Creating user-specific recognitions...');
        
        $users = User::where('status', 'Active')->take(5)->get();
        
        foreach ($users as $user) {
            // Create multiple recognitions for each user
            Recognition::factory()
                ->forUser($user)
                ->count(5)
                ->create();
        }
    }

    /**
     * Create recognitions by specific recognizers (for testing)
     */
    private function createRecognizerSpecificRecognitions(): void
    {
        $this->command->info('Creating recognizer-specific recognitions...');
        
        $recognizers = User::where('status', 'Active')->take(3)->get();
        
        foreach ($recognizers as $recognizer) {
            // Create multiple recognitions by each recognizer
            Recognition::factory()
                ->byRecognizer($recognizer)
                ->count(10)
                ->create();
        }
    }
}
