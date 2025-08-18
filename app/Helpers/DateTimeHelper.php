<?php

use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;

if (!function_exists('get_time_only')) {

    /**
     * Get the time only from a timestamp or date string in the format "hh:mm:ss".
     *
     * @param  int|string|null  $datetime
     * @return string
     */
    function get_time_only($datetime = null)
    {
        if ($datetime === null) {
            $timestamp = time(); // Use current timestamp if none is provided
        } else {
            $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
        }

        $time = date('H:i:s', $timestamp);

        return $time;
    }
}



if (!function_exists('get_date_only')) {
    /**
     * Get the date only from a timestamp in the format "Y-m-d".
     *
     * @param  Carbon|int|string|null  $timestamp
     * @return string
     */
    function get_date_only($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time(); // Use current timestamp if none is provided
        } elseif ($timestamp instanceof Carbon) {
            $timestamp = $timestamp->timestamp; // Convert Carbon instance to timestamp
        } elseif (is_string($timestamp)) {
            $timestamp = strtotime($timestamp); // Convert string to timestamp
        }

        $date = date('Y-m-d', $timestamp);

        return $date;
    }
}




if (!function_exists('show_date')) {

    /**
     * Format a timestamp or date to a custom formatted date.
     *
     * @param  string  $datetime
     * @param  string  $format
     * @return string
     */
    function show_date($datetime, $format = 'jS F, Y (l)')
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format);
    }
}

if (!function_exists('show_month')) {

    /**
     * Format a given date, datetime, or month (Y-m) to "Month Year" format.
     *
     * @param  string|null  $month
     * @return string
     */
    function show_month($month = null)
    {
        // If no $month is passed, use the current date
        $carbon = $month ? Carbon::parse($month) : Carbon::now();

        // Format to Month Year (e.g., September 2024)
        return $carbon->format('F Y');
    }
}




if (!function_exists('show_date_month_day')) {

    /**
     * Format a timestamp or date to a custom formatted date.
     *
     * @param  string  $datetime
     * @param  string  $format
     * @return string
     */
    function show_date_month_day($datetime, $format = 'd M, l')
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format);
    }
}


if (!function_exists('show_time')) {

    /**
     * Format a timestamp or date to a custom formatted time.
     *
     * @param  string  $datetime
     * @param  string|null  $format
     * @return string
     */
    function show_time($datetime, $format = null)
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format ?? 'h:i:s A');
    }
}



if (!function_exists('show_hr_min_sec')) {

    /**
     * Format a total time string to a human-readable format.
     *
     * @param  string  $totaltime
     * @return string
     */
    function show_hr_min_sec($totaltime = '00:00:00')
    {
        // Split the total time string into hours, minutes, and seconds
        list($hours, $minutes, $seconds) = explode(':', $totaltime);

        // Initialize the output string
        $output = '';

        // Determine the output based on the time components
        if ($hours == 0 && $minutes == 0 && $seconds == 0) {
            return NULL;
        }

        if ($hours > 0) {
            $output .= $hours . 'hr';
        }

        if ($minutes > 0) {
            // Add a space if hours were included
            if ($output) {
                $output .= ' ';
            }
            $output .= $minutes . 'min';
        }

        if ($seconds > 0) {
            // Add a space if hours or minutes were included
            if ($output) {
                $output .= ' ';
            }
            $output .= $seconds . 'sec';
        }

        return trim($output); // Trim any extra spaces
    }
}



if (!function_exists('show_date_time')) {

    /**
     * Format a timestamp or date to a custom formatted date and time.
     *
     * @param  string  $datetime
     * @param  string  $format
     * @return string
     */
    function show_date_time($datetime, $format = 'jS F, Y (l) \a\t h:i:s A')
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format);
    }
}


if (!function_exists('date_time_ago')) {

    /**
     * Get the time difference between a timestamp or date and the current time in a human-readable format.
     *
     * @param  string  $datetime
     * @return string
     */
    function date_time_ago($datetime)
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->diffForHumans();
    }
}



if (!function_exists('total_day_difference')) {

    /**
     * Get the total time difference between a start date and an end date in the format "x years y months z days".
     *
     * @param  string|int  $startDate  The start date or timestamp.
     * @param  string|int|null  $endDate  The end date or timestamp. Defaults to current date if not provided.
     * @return string
     */
    function total_day_difference($startDate, $endDate = null)
    {
        // Parse the start date
        $startDateTime = Carbon::parse($startDate);

        // If no end date is provided, default to the current date
        $endDateTime = $endDate ? Carbon::parse($endDate) : Carbon::now();

        // Get the difference as an interval
        $diff = $endDateTime->diff($startDateTime);

        // Create an output string depending on the difference
        $output = '';

        if ($diff->y > 0) {
            $output .= $diff->y . ' year' . ($diff->y > 1 ? 's ' : ' ');
        }

        if ($diff->m > 0) {
            $output .= $diff->m . ' month' . ($diff->m > 1 ? 's ' : ' ');
        }

        if ($diff->d > 0 || $output === '') { // Show days if the difference includes days, or no years/months.
            $output .= $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        }

        // Trim any extra spaces and return the result
        return trim($output);
    }
}



if (!function_exists('get_total_time_hh_mm_ss')) {
    /**
     * Calculate the total time difference between two timestamps in hh:mm:ss format.
     *
     * @param string $startTime The start time in 'Y-m-d H:i:s' or 'H:i:s' format.
     * @param string $endTime The end time in 'Y-m-d H:i:s' or 'H:i:s' format.
     * @return string Total time difference in 'hh:mm:ss' format.
     * @throws Exception if the input format is invalid.
     */
    function get_total_time_hh_mm_ss($startTime, $endTime)
    {
        $now = new DateTime(); // Get the current date

        // Convert time-only strings into full datetime objects
        $start = DateTime::createFromFormat('Y-m-d H:i', $now->format('Y-m-d') . ' ' . $startTime);
        $end = DateTime::createFromFormat('Y-m-d H:i', $now->format('Y-m-d') . ' ' . $endTime);

        // If end time is earlier than start time, it means it's on the next day
        if ($end < $start) {
            $end->modify('+1 day'); // Move end time to the next day
        }

        // Calculate the difference
        $interval = $start->diff($end);

        // Calculate total hours correctly
        $totalHours = ($interval->days * 24) + $interval->h;

        // Format as 'hh:mm:ss'
        return sprintf('%02d:%02d:%02d', $totalHours, $interval->i, $interval->s);
    }
}



if (!function_exists('total_time_difference')) {

    /**
     * Get the total time difference between a start time and end time in the format "hh:mm:ss".
     *
     * @param  string  $startTime
     * @param  string  $endTime
     * @return string
     */
    function total_time_difference($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);

        $diff = $endDateTime->diff($startDateTime);

        $hours = $diff->format('%H');
        $minutes = $diff->format('%I');
        $seconds = $diff->format('%S');

        return sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
    }
}

if (!function_exists('get_total_hour')) {

    /**
     * Get the total hours difference between a start time and end time.
     *
     * @param  string  $startTime
     * @param  string  $endTime
     * @return int
     */
    function get_total_hour($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);

        $diffInHours = $endDateTime->diffInHours($startDateTime);

        return $diffInHours;
    }
}

if (!function_exists('total_day')) {

    /**
     * Calculate total days between $startDate and $endDate.
     * Return total years, months, and days.
     *
     * @param  string  $startDate
     * @param  string|null  $endDate
     * @return string
     */
    function total_day($startDate, $endDate = null)
    {
        // Convert start and end dates to DateTime objects
        $startDate = new DateTime($startDate);
        $endDate = $endDate ? new DateTime($endDate) : new DateTime();

        // Calculate the interval between the two dates
        $interval = $startDate->diff($endDate);

        // Extract years, months, and days from the interval
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        // Build the formatted total day string
        $formattedTotalDay = '';

        if ($years > 0) {
            $formattedTotalDay .= "$years " . ($years == 1 ? 'Year' : 'Years') . ' ';
        }

        if ($months > 0) {
            $formattedTotalDay .= "$months " . ($months == 1 ? 'Month' : 'Months') . ' ';
        }

        $formattedTotalDay .= "$days " . ($days == 1 ? 'Day' : 'Days');

        // Return the formatted total day
        return $formattedTotalDay;
    }
}




if (!function_exists('total_time_with_min_hour')) {

    /**
     * Format total time and apply text color based on conditions.
     *
     * @param  string  $totalTime
     * @return string
     */
    function total_time_with_min_hour($totalTime, $minHour = 8)
    {
        // Parse the total time string
        $timeComponents = explode(':', $totalTime);
        $hours = (int)$timeComponents[0];
        $minutes = (int)$timeComponents[1];
        $seconds = (int)$timeComponents[2];

        // Determine text color based on conditions
        $textColor = 'text-success'; // Default color

        if ($hours < $minHour) {
            $textColor = 'text-danger';
        } elseif ($hours > ($minHour + 2)) {
            $textColor = 'text-warning';
        }

        // Format the total time based on relevant parts
        if ($hours > 0) {
            $formattedTotalTime = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            $formattedTotalTime = sprintf('%02dm %02ds', $minutes, $seconds);
        } else {
            $formattedTotalTime = sprintf('%02ds', $seconds);
        }

        // Return the formatted total time with the appropriate text color
        return "<span class='$textColor'>$formattedTotalTime</span>";
    }
}


if (!function_exists('total_time')) {

    /**
     * Format total time.
     *
     * @param  string  $totalTime
     * @return string
     */
    function total_time($totalTime)
    {
        // dd($totalTime);
        if (is_null($totalTime)) {
            return NULL;
        }
        // Parse the total time string
        $timeComponents = explode(':', $totalTime);
        $hours = (int)$timeComponents[0];
        $minutes = (int)$timeComponents[1];
        $seconds = (int)$timeComponents[2];

        // Format the total time based on relevant parts
        if ($hours > 0) {
            $formattedTotalTime = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            $formattedTotalTime = sprintf('%02dm %02ds', $minutes, $seconds);
        } else {
            $formattedTotalTime = sprintf('%02ds', $seconds);
        }

        // Return the formatted total time
        return $formattedTotalTime;
    }
}


if (!function_exists('show_leave')) {

    /**
     * Format a leave interval coming from either a CarbonInterval or an "hh:mm:ss" string.
     * Returns "No Leave" when the interval represents zero time.
     *
     * @param  CarbonInterval|string|null  $interval
     * @param  string  $zeroText
     * @return string
     */
    function show_leave($interval, $zeroText = 'No Leave')
    {
        if ($interval instanceof CarbonInterval) {
            $isZero = ($interval->years == 0
                && $interval->months == 0
                && $interval->weeks == 0
                && $interval->days == 0
                && $interval->hours == 0
                && $interval->minutes == 0
                && $interval->seconds == 0);

            if ($isZero) {
                return $zeroText;
            }

            return $interval->forHumans();
        }

        if (is_string($interval)) {
            $parts = explode(':', $interval);
            if (count($parts) === 3) {
                [$h, $m, $s] = $parts;
                if (((int) $h) === 0 && ((int) $m) === 0 && ((int) $s) === 0) {
                    return $zeroText;
                }
            }

            return $interval;
        }

        return $zeroText;
    }
}

if (!function_exists('upcoming_birthday')) {

    /**
     * Get the time remaining until the next birthday or print "Happy Birthday" if today is the birthday.
     *
     * @param  string  $birthDate  The birthdate in 'Y-m-d' format or any date parsable by Carbon.
     * @return string  The remaining time until the next birthday, e.g., "11 Months 3 Days to go" or "Happy Birthday".
     */
    function upcoming_birthday($birthDate)
    {
        // Parse the birthdate and get the current date
        $birthDate = Carbon::parse($birthDate)->startOfDay();
        $now = Carbon::now()->startOfDay();

        // Get the birthdate for the current year
        $nextBirthday = Carbon::create($now->year, $birthDate->month, $birthDate->day);

        // If the birthday has already passed this year, move it to next year
        if ($nextBirthday->isPast()) {
            $nextBirthday = $nextBirthday->addYear();
        }

        // Get the difference between now and the next birthday
        $diff = $now->diff($nextBirthday);

        // Extract years, months, and days from the difference
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        // Prepare the result string
        $result = [];

        if ($years > 0) {
            $result[] = $years . ' ' . Str::plural('Year', $years);
        }

        if ($months > 0) {
            $result[] = $months . ' ' . Str::plural('Month', $months);
        }

        if ($days > 0) {
            $result[] = $days . ' ' . Str::plural('Day', $days);
        }

        // Return the result string or default message if empty
        return !empty($result) ? implode(' ', $result) . ' to go' : 'No time difference available';
    }
}


if (!function_exists('is_today_birthday')) {

    /**
     * Check if today is the user's birthday.
     *
     * @param  string  $birthDate  The birthdate in 'Y-m-d' format.
     * @return bool  Returns true if today is the birthday, false otherwise.
     */
    function is_today_birthday($birthDate)
    {
        // Parse the birthdate, ignoring the year and only comparing the month and day
        $birthDate = Carbon::parse($birthDate);
        $today = Carbon::now();

        // Check if today is the birthday by comparing month and day
        return $birthDate->isBirthday($today);
    }
}



if (!function_exists('total_regular_working_days')) {
    /**
     * Calculate the number of working days in a given month, excluding weekends and holidays.
     *
     * @param string|null $month The month in 'Y-m' format (e.g., '2024-09'). If null, defaults to the previous month.
     * @return int The number of working days in the specified month.
     */
    function total_regular_working_days(string $month = null): int
    {
        // If no month is passed, use the previous month
        if (is_null($month)) {
            $month = Carbon::now()->subMonth()->format('Y-m');
        }

        // Get the total number of days in the month
        $daysInMonth = Carbon::parse($month)->daysInMonth;

        // Get the list of active weekend days (e.g., ['Saturday', 'Sunday'])
        $weekendDays = Weekend::getActiveWeekendDays();

        // Get the holidays for the specified month
        $holidays = Holiday::whereMonth('date', Carbon::parse($month)->month)
                            ->whereYear('date', Carbon::parse($month)->year)
                            ->pluck('date')
                            ->toArray();

        // Create a collection of days in the month and filter out the weekends and holidays
        $workingDays = collect(range(1, $daysInMonth))
            ->map(function ($day) use ($month) {
                return Carbon::parse($month . '-' . $day);
            })
            ->filter(function ($date) use ($weekendDays, $holidays) {
                // Exclude weekends and holidays
                return !in_array($date->format('l'), $weekendDays) && !in_array($date->toDateString(), $holidays);
            });

        // Return the count of working days
        return $workingDays->count();
    }
}


if (!function_exists('task_deadline_status')) {
    /**
     * Calculate task deadline status and return appropriate badge with days remaining.
     *
     * @param string|Carbon|null $deadline The task deadline date
     * @param string|Carbon|null $createdAt The task creation date (optional, defaults to current date)
     * @return array Returns array with 'badge_class', 'text', and 'days_remaining'
     */
    function task_deadline_status($deadline, $createdAt = null)
    {
        if (is_null($deadline)) {
            return [
                'badge_class' => 'bg-success',
                'text' => 'Ongoing Task',
                'days_remaining' => null
            ];
        }

        $deadlineDate = Carbon::parse($deadline)->startOfDay();
        $today = Carbon::now()->startOfDay();
        $createdDate = $createdAt ? Carbon::parse($createdAt)->startOfDay() : $today;

        // Calculate days remaining (can be negative if overdue)
        $daysRemaining = $today->diffInDays($deadlineDate, false);

        // If deadline is overdue
        if ($daysRemaining < 0) {
            $overdueDays = abs($daysRemaining);
            return [
                'badge_class' => 'bg-danger',
                'text' => $overdueDays . ' day' . ($overdueDays > 1 ? 's' : '') . ' overdue',
                'days_remaining' => $daysRemaining
            ];
        }

        // Calculate total duration from creation to deadline
        $totalDuration = $createdDate->diffInDays($deadlineDate);

        // If total duration is 0 (same day task), treat as urgent
        if ($totalDuration == 0) {
            return [
                'badge_class' => 'bg-warning',
                'text' => 'Due today',
                'days_remaining' => $daysRemaining
            ];
        }

        // Calculate elapsed time percentage
        $elapsedDays = $createdDate->diffInDays($today);
        $elapsedPercentage = $totalDuration > 0 ? ($elapsedDays / $totalDuration) * 100 : 0;

        // Determine badge class based on remaining time percentage
        if ($elapsedPercentage <= 50) {
            // 50% or more time remaining - success (green)
            $badgeClass = 'bg-success';
        } else {
            // Less than 50% time remaining - warning (yellow)
            $badgeClass = 'bg-warning';
        }

        // Format the text
        $text = $daysRemaining . ' day' . ($daysRemaining > 1 ? 's' : '') . ' remaining';

        return [
            'badge_class' => $badgeClass,
            'text' => $text,
            'days_remaining' => $daysRemaining
        ];
    }
}
