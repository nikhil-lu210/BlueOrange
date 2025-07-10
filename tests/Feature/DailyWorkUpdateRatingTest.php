<?php

namespace Tests\Feature;

use App\Models\DailyWorkUpdate\DailyWorkUpdate;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DailyWorkUpdateRatingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $teamLeader;
    protected $dailyWorkUpdate;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create();
        $this->teamLeader = User::factory()->create();

        // Create a daily work update manually
        $this->dailyWorkUpdate = DailyWorkUpdate::create([
            'user_id' => $this->user->id,
            'team_leader_id' => $this->teamLeader->id,
            'date' => now()->format('Y-m-d'),
            'work_update' => 'Test work update content',
            'progress' => 50,
            'note' => 'Test note',
            'rating' => null,
            'comment' => null
        ]);
    }

    /** @test */
    public function team_leader_can_rate_daily_work_update_via_ajax()
    {
        $this->actingAs($this->teamLeader);

        $response = $this->postJson(
            route('administration.daily_work_update.update', $this->dailyWorkUpdate),
            [
                'rating' => 4
            ],
            [
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        );

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Daily Work Update Has Been Rated Successfully.',
                    'rating' => 4
                ]);

        $this->assertDatabaseHas('daily_work_updates', [
            'id' => $this->dailyWorkUpdate->id,
            'rating' => 4
        ]);
    }

    /** @test */
    public function rating_validation_works_for_ajax_requests()
    {
        $this->actingAs($this->teamLeader);

        // Test invalid rating (too high)
        $response = $this->postJson(
            route('administration.daily_work_update.update', $this->dailyWorkUpdate),
            [
                'rating' => 6
            ],
            [
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        );

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['rating']);

        // Test invalid rating (too low)
        $response = $this->postJson(
            route('administration.daily_work_update.update', $this->dailyWorkUpdate),
            [
                'rating' => 0
            ],
            [
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        );

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['rating']);
    }

    /** @test */
    public function ajax_request_returns_error_on_exception()
    {
        $this->actingAs($this->teamLeader);

        // Try to update a non-existent daily work update
        $response = $this->postJson(
            route('administration.daily_work_update.update', 99999),
            [
                'rating' => 4
            ],
            [
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        );

        $response->assertStatus(404);
    }

    /** @test */
    public function non_ajax_request_still_works_with_redirect()
    {
        $this->actingAs($this->teamLeader);

        $response = $this->post(
            route('administration.daily_work_update.update', $this->dailyWorkUpdate),
            [
                'rating' => 5
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('daily_work_updates', [
            'id' => $this->dailyWorkUpdate->id,
            'rating' => 5
        ]);
    }
}
