<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Penalty\Penalty;
use App\Models\Attendance\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class PenaltyModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\Permission\\PermissionsTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\Role\\RolesTableSeeder']);
        
        // Create Super Admin user
        $this->superAdmin = User::factory()->create();
        $superAdminRole = Role::findByName('Super Admin');
        $this->superAdmin->assignRole($superAdminRole);
        
        // Create Employee user
        $this->employee = User::factory()->create();
    }

    /** @test */
    public function penalty_model_can_be_created()
    {
        $penalty = Penalty::create([
            'user_id' => $this->employee->id,
            'attendance_id' => 1, // Assuming attendance exists
            'type' => 'Dress Code Violation',
            'total_time' => 30,
            'reason' => 'Employee was not following dress code policy.',
            'creator_id' => $this->superAdmin->id,
        ]);

        $this->assertInstanceOf(Penalty::class, $penalty);
        $this->assertEquals('Dress Code Violation', $penalty->type);
        $this->assertEquals(30, $penalty->total_time);
        $this->assertEquals('30m', $penalty->total_time_formatted);
    }

    /** @test */
    public function penalty_types_are_available()
    {
        $types = Penalty::getPenaltyTypes();
        
        $expectedTypes = [
            'Dress Code Violation',
            'Unauthorized Break',
            'Bad Attitude',
            'Unexcused Absence',
            'Unauthorized Leave',
            'Unauthorized Overtime',
            'Other'
        ];

        $this->assertEquals($expectedTypes, $types);
    }

    /** @test */
    public function super_admin_can_access_penalty_index()
    {
        $this->actingAs($this->superAdmin);
        
        $response = $this->get(route('administration.penalty.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('administration.penalty.index');
    }

    /** @test */
    public function super_admin_can_access_penalty_create()
    {
        $this->actingAs($this->superAdmin);
        
        $response = $this->get(route('administration.penalty.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('administration.penalty.create');
    }

    /** @test */
    public function penalty_relationships_work_correctly()
    {
        // Create an attendance record first
        $attendance = Attendance::factory()->create([
            'user_id' => $this->employee->id,
        ]);

        $penalty = Penalty::create([
            'user_id' => $this->employee->id,
            'attendance_id' => $attendance->id,
            'type' => 'Bad Attitude',
            'total_time' => 60,
            'reason' => 'Employee showed bad attitude towards customers.',
            'creator_id' => $this->superAdmin->id,
        ]);

        // Test relationships
        $this->assertEquals($this->employee->id, $penalty->user->id);
        $this->assertEquals($attendance->id, $penalty->attendance->id);
        $this->assertEquals($this->superAdmin->id, $penalty->creator->id);
    }
}
