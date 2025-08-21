<?php

namespace App\Console\Commands\Administration\User;

use App\Mail\Administration\User\BirthdayNotifyInteractionsMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User\Employee\Employee;
use App\Mail\Administration\User\BirthdayWishMail;
use App\Mail\Administration\User\UpcomingBirthdayNotifyMail;
use App\Models\User;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:birthday-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday wishes to employees and notify their user interactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get today's date and 3 days ahead
        $today = Carbon::now();
        $threeDaysFromNow = $today->copy()->addDays(3);

        // Get employees whose birthday is today
        $employees = Employee::whereMonth('birth_date', $today->month)
                                ->whereDay('birth_date', $today->day)
                                ->get();

        if ($employees->isEmpty()) {
            $this->info('No birthdays today.');
        }

        foreach ($employees as $employee) {
            // Send birthday wish to employee
            Mail::to($employee->official_email)->queue(new BirthdayWishMail($employee));
            // sleep(2); // Pause for 2 seconds to avoid hitting Mailtrap's rate limit
            $this->info("Birthday Wishing email sent to {$employee->alias_name}.");

            // Check if user_interactions is empty before looping
            if ($employee->user && $employee->user->user_interactions->isNotEmpty()) {
                foreach ($employee->user->user_interactions ?? collect([]) as $interaction) {
                    if ($interaction->employee) {
                        Mail::to($interaction->employee->official_email)->queue(new BirthdayNotifyInteractionsMail($employee, $interaction));
                        // sleep(2); // Pause for 2 seconds to avoid hitting Mailtrap's rate limit
                    }
                }
            }

            $this->info("Birthday emails sent to {$employee->alias_name}'s interactions.");
        }


        // Check if any employee's birthday is in 3 days
        $employeesInThreeDays = Employee::whereMonth('birth_date', $threeDaysFromNow->month)
                                        ->whereDay('birth_date', $threeDaysFromNow->day)
                                        ->get();

        if ($employeesInThreeDays->isEmpty()) {
            $this->info('No birthdays in 3 days.');
        } else {
            foreach ($employeesInThreeDays as $employee) {
                $notifiableUsers = User::with('employee')->whereStatus('Active')->get();

                foreach ($notifiableUsers as $notifiableUser) {
                    if ($notifiableUser->hasAnyPermission(['User Everything', 'User Update'])) {
                        Mail::to($notifiableUser->employee->official_email)->queue(new UpcomingBirthdayNotifyMail($employee, $notifiableUser));
                        $this->info("Birthday notification sent to {$notifiableUser->employee->alias_name} for {$employee->alias_name}.");
                    }
                }
            }
        }
    }
}
