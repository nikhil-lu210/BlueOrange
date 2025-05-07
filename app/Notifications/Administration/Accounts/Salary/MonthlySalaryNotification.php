<?php

namespace App\Notifications\Administration\Accounts\Salary;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MonthlySalaryNotification extends Notification
{
    use Queueable;

    protected $monthly_salary;

    /**
     * Create a new notification instance.
     */
    public function __construct($monthly_salary)
    {
        $this->monthly_salary = $monthly_salary;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.accounts.salary.monthly.show', ['monthly_salary' => $this->monthly_salary]);
        return [
            'url'   => $url,
            'icon'   => 'currency-taka',
            'title'   => 'Salary Paid For ' . show_month($this->monthly_salary->for_month),
            'message'     => show_month($this->monthly_salary->for_month) . ' Salary Has Been Paid By '. $this->monthly_salary->payer->alias_name,
        ];
    }
}
