<?php

namespace Database\Seeders\Event;

use App\Models\Event\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to be organizers
        $users = User::where('status', 'Active')->take(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No active users found. Please run UserSeeder first.');
            return;
        }

        // Create sample events
        $events = [
            [
                'title' => 'Team Meeting - Q1 Review',
                'description' => 'Quarterly team meeting to review Q1 performance and plan Q2 objectives.',
                'start_date' => now()->addDays(2)->format('Y-m-d'),
                'end_date' => now()->addDays(2)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'location' => 'Conference Room A',
                'event_type' => 'meeting',
                'status' => 'published',
                'is_all_day' => false,
                'color' => '#3788d8',
                'max_participants' => 20,
                'is_public' => true,
                'reminder_before' => 15,
                'reminder_unit' => 'minutes',
            ],
            [
                'title' => 'Employee Training - New Software',
                'description' => 'Training session for the new project management software.',
                'start_date' => now()->addDays(5)->format('Y-m-d'),
                'end_date' => now()->addDays(5)->format('Y-m-d'),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'location' => 'Training Room B',
                'event_type' => 'training',
                'status' => 'published',
                'is_all_day' => false,
                'color' => '#28a745',
                'max_participants' => 15,
                'is_public' => true,
                'reminder_before' => 1,
                'reminder_unit' => 'hours',
            ],
            [
                'title' => 'Company Anniversary Celebration',
                'description' => 'Annual company anniversary celebration with team building activities.',
                'start_date' => now()->addDays(15)->format('Y-m-d'),
                'end_date' => now()->addDays(15)->format('Y-m-d'),
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'location' => 'Main Hall',
                'event_type' => 'celebration',
                'status' => 'published',
                'is_all_day' => false,
                'color' => '#ffc107',
                'max_participants' => 100,
                'is_public' => true,
                'reminder_before' => 1,
                'reminder_unit' => 'days',
            ],
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Annual technology conference featuring industry experts and networking opportunities.',
                'start_date' => now()->addDays(30)->format('Y-m-d'),
                'end_date' => now()->addDays(32)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'location' => 'Convention Center',
                'event_type' => 'conference',
                'status' => 'published',
                'is_all_day' => true,
                'color' => '#6f42c1',
                'max_participants' => 200,
                'is_public' => true,
                'reminder_before' => 7,
                'reminder_unit' => 'days',
            ],
            [
                'title' => 'Workshop - Leadership Skills',
                'description' => 'Interactive workshop focused on developing leadership and management skills.',
                'start_date' => now()->addDays(10)->format('Y-m-d'),
                'end_date' => now()->addDays(10)->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'location' => 'Workshop Room C',
                'event_type' => 'workshop',
                'status' => 'published',
                'is_all_day' => false,
                'color' => '#fd7e14',
                'max_participants' => 25,
                'is_public' => true,
                'reminder_before' => 2,
                'reminder_unit' => 'hours',
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create(array_merge($eventData, [
                'organizer_id' => $users->random()->id,
                'current_participants' => 0,
            ]));

            // Add some random participants
            $randomUsers = $users->where('id', '!=', $event->organizer_id)->random(rand(2, 5));
            foreach ($randomUsers as $user) {
                $event->participants()->create([
                    'user_id' => $user->id,
                    'status' => rand(0, 1) ? 'accepted' : 'invited',
                ]);
            }

            // Add organizer as participant
            $event->participants()->create([
                'user_id' => $event->organizer_id,
                'status' => 'accepted',
            ]);

            // Update current participants count
            $event->update([
                'current_participants' => $event->participants()->where('status', 'accepted')->count()
            ]);
        }

        $this->command->info('Events seeded successfully!');
    }
}
