<?php

namespace App\Imports\Administration\User;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Leave\LeaveAllowed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\User\Employee\Employee;
use App\Models\EmployeeShift\EmployeeShift;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $roleId;

    public function __construct($roleId)
    {
        $this->roleId = $roleId; // Role passed from the controller
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // dd($row->toArray());
            DB::transaction(function () use ($row) {
                $userId = (string)$row['userid']; // Cast userId to string

                // Create or update user
                $user = User::updateOrCreate(
                    ['userid' => $userId],
                    [
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'name' => $row['first_name'].' '.$row['last_name'],
                        'email' => $row['email'],
                        'password' => bcrypt($row['password']),
                        'status' => $row['status'] ?? 'Active',
                    ]
                );

                // Assign role to user
                $user->roles()->sync([$this->roleId]);

                // Create or update employee
                Employee::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'joining_date' => Carbon::parse($row['joining_date'])->format('Y-m-d'),
                        'alias_name' => $row['alias_name'],
                        'father_name' => $row['father_name'],
                        'mother_name' => $row['mother_name'],
                        'birth_date' => Carbon::parse($row['birth_date'])->format('Y-m-d'),
                        'personal_email' => $row['personal_email'],
                        'personal_contact_no' => $row['personal_contact_no'],
                        'official_email' => $row['official_email'] ?? null,
                        'official_contact_no' => $row['official_contact_no'] ?? null,
                    ]
                );

                // Create or update employee shift
                EmployeeShift::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'start_time' => $row['start_time'] ?? '15:00:00',
                        'end_time' => $row['end_time'] ?? '23:00:00',
                        'total_time' => $row['total_time'] ?? '08:00:00',
                        'implemented_from' => Carbon::parse($row['joining_date'])->format('Y-m-d')
                    ]
                );

                // Create or update leave allowances
                LeaveAllowed::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'earned_leave' => $row['earned_leave'] ?? '00:00:00',
                        'casual_leave' => $row['casual_leave'] ?? '00:00:00',
                        'sick_leave' => $row['sick_leave'] ?? '00:00:00',
                        'implemented_from' => '01-01',
                        'implemented_to' => '12-31'
                    ]
                );
            });
        }
    }

    public function rules(): array
    {
        return [
            '*.userid' => 'required|alpha_num|unique:users,userid',
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.email' => 'required|email|unique:users,email',
            '*.password' => 'required|alpha_num|min:6',
            '*.joining_date' => 'required|date',
            '*.birth_date' => 'required|date',
            '*.personal_email' => 'required|email|unique:employees,personal_email',
            '*.personal_contact_no' => 'required|string|unique:employees,personal_contact_no',
            '*.official_email' => 'nullable|email|unique:employees,official_email',
            '*.official_contact_no' => 'nullable|string|unique:employees,official_contact_no',
            '*.start_time' => 'required|date_format:H:i',
            '*.end_time' => 'required|date_format:H:i|after:*.start_time',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.userid.unique' => 'The User ID :input is already registered.',
            '*.email.unique' => 'The email :input is already registered.',
            '*.personal_email.unique' => 'The personal email :input is already registered.',
            '*.personal_contact_no.unique' => 'The personal contact number :input is already registered.',
            '*.end_time_time.after' => 'The shift end time must be after the start time.',
            '*.end_time_date.after_or_equal' => 'The shift end date must be on or after the start date.',
        ];
    }
}
