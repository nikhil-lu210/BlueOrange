<?php

namespace App\Notifications\Administration\Recognition;

use App\Models\User\Employee\EmployeeRecognition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EmployeeRecognitionPublished extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public EmployeeRecognition $recognition)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $r = $this->recognition;
        $monthLabel = Carbon::parse($r->month)->format('F Y');
        $score = (int) $r->total_score;
        $badge = $this->badgeForScore($score);
        $teamLeaderName = optional($r->teamLeader)->name ?? 'Your Team Leader';

        return (new MailMessage)
            ->subject("Your Monthly Recognition for {$monthLabel}")
            ->greeting("Congratulations!")
            ->line("You have been recognized for {$monthLabel} by {$teamLeaderName}.")
            ->line("Badge: {$badge['emoji']} {$badge['label']}")
            ->line("Total Score: {$score}/100")
            ->line('Keep up the great work!');
    }

    public function toArray(object $notifiable): array
    {
        $r = $this->recognition;
        $monthLabel = Carbon::parse($r->month)->format('F Y');
        $score = (int) $r->total_score;
        $badge = $this->badgeForScore($score);

        return [
            'type' => 'employee_recognition_published',
            'month' => $r->month,
            'month_label' => $monthLabel,
            'total_score' => $score,
            'badge' => $badge,
            'team_leader_id' => $r->team_leader_id,
            'team_leader_name' => optional($r->teamLeader)->name,
            'message' => "Your recognition for {$monthLabel} is published: {$badge['emoji']} {$badge['label']} ({$score}/100)",
        ];
    }

    private function badgeForScore(int $score): array
    {
        $code = match (true) {
            $score >= 90 => 'platinum',
            $score >= 80 => 'gold',
            $score >= 70 => 'silver',
            $score >= 60 => 'bronze',
            $score >= 50 => 'rising',
            default       => 'learner',
        };
        $label = match ($code) {
            'platinum' => 'Platinum Performer',
            'gold'     => 'Gold Achiever',
            'silver'   => 'Silver Contributor',
            'bronze'   => 'Bronze Supporter',
            'rising'   => 'Rising Star',
            'learner'  => 'Learner',
        };
        $emoji = match ($code) {
            'platinum' => '🌟',
            'gold'     => '🥇',
            'silver'   => '🥈',
            'bronze'   => '🥉',
            'rising'   => '💪',
            'learner'  => '🌱',
        };
        return compact('code', 'label', 'emoji');
    }
}
