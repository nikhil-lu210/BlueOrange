<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Leave\LeaveAllowed;
use App\Models\Leave\LeaveAvailable;
use App\Models\Leave\LeaveHistory;
use App\Services\Administration\Leave\LeaveHistoryService;
use App\Services\Administration\Leave\LeaveValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class LeaveModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $leaveHistoryService;
    protected $leaveValidationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->leaveHistoryService = app(LeaveHistoryService::class);
        $this->leaveValidationService = app(LeaveValidationService::class);

        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_active_leave_allowed_record()
    {
        // Create an active leave allowed record
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('leave_alloweds', [
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_sync_leave_balances()
    {
        // Create active leave allowed
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        // Sync leave balances
        $this->leaveHistoryService->syncLeaveBalances($this->user, 2025);

        // Check if leave available record was created
        $this->assertDatabaseHas('leave_availables', [
            'user_id' => $this->user->id,
            'for_year' => 2025,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
        ]);
    }

    /** @test */
    public function it_can_validate_leave_balance()
    {
        // Create active leave allowed
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        // Sync leave balances
        $this->leaveHistoryService->syncLeaveBalances($this->user, 2025);

        // Test validation for sufficient balance
        $result = $this->leaveValidationService->validateLeaveBalance(
            $this->user,
            'Earned',
            '08:00:00',
            '2025-01-15'
        );

        $this->assertTrue($result['is_sufficient']);
        $this->assertEquals('35:00:00', $result['current_balance']);
        $this->assertEquals('27:00:00', $result['remaining_balance']);

        // Test validation for insufficient balance
        $result = $this->leaveValidationService->validateLeaveBalance(
            $this->user,
            'Earned',
            '40:00:00',
            '2025-01-15'
        );

        $this->assertFalse($result['is_sufficient']);
        $this->assertStringContainsString('Insufficient earned leave balance', $result['message']);
    }

    /** @test */
    public function it_can_get_leave_balance_summary()
    {
        // Create active leave allowed
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        // Sync leave balances
        $this->leaveHistoryService->syncLeaveBalances($this->user, 2025);

        // Get balance summary
        $summary = $this->leaveValidationService->getLeaveBalanceSummary($this->user, 2025);

        $this->assertEquals(2025, $summary['year']);
        $this->assertEquals('35:00:00', $summary['earned_leave']['allowed']);
        $this->assertEquals('35:00:00', $summary['earned_leave']['available']);
        $this->assertEquals('00:00:00', $summary['earned_leave']['used']);
    }

    /** @test */
    public function it_can_approve_leave_and_update_balance()
    {
        // Create active leave allowed
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '35:00:00',
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        // Create leave history
        $leaveHistory = LeaveHistory::create([
            'user_id' => $this->user->id,
            'leave_allowed_id' => $leaveAllowed->id,
            'date' => '2025-01-15',
            'total_leave' => '08:00:00',
            'type' => 'Earned',
            'reason' => 'Test leave',
            'status' => 'Pending',
        ]);

        // Sync initial balances
        $this->leaveHistoryService->syncLeaveBalances($this->user, 2025);

        // Approve the leave
        $request = new \Illuminate\Http\Request();
        $request->merge(['is_paid_leave' => 'Paid']);

        $this->leaveHistoryService->approve($request, $leaveHistory);

        // Check if leave was approved
        $this->assertDatabaseHas('leave_histories', [
            'id' => $leaveHistory->id,
            'status' => 'Approved',
        ]);

        // Check if balance was updated
        $this->assertDatabaseHas('leave_availables', [
            'user_id' => $this->user->id,
            'for_year' => 2025,
            'earned_leave' => '27:00:00', // 35:00:00 - 08:00:00
        ]);
    }

    /** @test */
    public function it_prevents_approval_with_insufficient_balance()
    {
        // Create active leave allowed with limited balance
        $leaveAllowed = LeaveAllowed::create([
            'user_id' => $this->user->id,
            'earned_leave' => '05:00:00', // Only 5 hours
            'casual_leave' => '10:00:00',
            'sick_leave' => '15:00:00',
            'implemented_from' => '01-01',
            'implemented_to' => '12-31',
            'is_active' => true,
        ]);

        // Create leave history requesting more than available
        $leaveHistory = LeaveHistory::create([
            'user_id' => $this->user->id,
            'leave_allowed_id' => $leaveAllowed->id,
            'date' => '2025-01-15',
            'total_leave' => '08:00:00', // Requesting 8 hours
            'type' => 'Earned',
            'reason' => 'Test leave',
            'status' => 'Pending',
        ]);

        // Sync initial balances
        $this->leaveHistoryService->syncLeaveBalances($this->user, 2025);

        // Try to approve the leave - should fail
        $request = new \Illuminate\Http\Request();
        $request->merge(['is_paid_leave' => 'Paid']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient earned leave balance');

        $this->leaveHistoryService->approve($request, $leaveHistory);
    }
}
