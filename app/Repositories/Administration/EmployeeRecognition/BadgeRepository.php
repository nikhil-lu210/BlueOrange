<?php

declare(strict_types=1);

namespace App\Repositories\Administration\EmployeeRecognition;

class BadgeRepository
{
    /**
     * Get badge code for a given score
     */
    public function getCodeForScore(int $score): string
    {
        return match (true) {
            $score >= 90 => 'platinum',
            $score >= 80 => 'gold',
            $score >= 70 => 'silver',
            $score >= 60 => 'bronze',
            $score >= 50 => 'rising',
            default      => 'learner',
        };
    }

    /**
     * Get badge label for a given score
     */
    public function getLabelForScore(int $score): string
    {
        return match ($this->getCodeForScore($score)) {
            'platinum' => 'Platinum Performer',
            'gold'     => 'Gold Achiever',
            'silver'   => 'Silver Contributor',
            'bronze'   => 'Bronze Supporter',
            'rising'   => 'Rising Star',
            'learner'  => 'Learner',
        };
    }

    /**
     * Get badge emoji for a given score
     */
    public function getEmojiForScore(int $score): string
    {
        return match ($this->getCodeForScore($score)) {
            'platinum' => '🌟',
            'gold'     => '🥇',
            'silver'   => '🥈',
            'bronze'   => '🥉',
            'rising'   => '💪',
            'learner'  => '🌱',
        };
    }

    /**
     * Get score range for a given badge code
     */
    public function getScoreRangeForCode(string $badgeCode): array
    {
        return match ($badgeCode) {
            'platinum' => [90, 100],
            'gold'     => [80, 89],
            'silver'   => [70, 79],
            'bronze'   => [60, 69],
            'rising'   => [50, 59],
            'learner'  => [0, 49],
            default    => [0, 100],
        };
    }

    /**
     * Get CSS class for badge display
     */
    public function getClassForCode(string $badgeCode): string
    {
        return match ($badgeCode) {
            'platinum' => 'bg-success',
            'gold'     => 'bg-warning',
            'silver'   => 'bg-primary',
            'bronze'   => 'bg-danger',
            'rising'   => 'bg-dark',
            'learner'  => 'bg-label-dark',
            default    => 'bg-secondary',
        };
    }

    /**
     * Get complete badge information for a score
     */
    public function getBadgeForScore(int $score): array
    {
        $code = $this->getCodeForScore($score);
        return [
            'code'  => $code,
            'label' => $this->getLabelForScore($score),
            'emoji' => $this->getEmojiForScore($score),
            'class' => $this->getClassForCode($code),
        ];
    }
}