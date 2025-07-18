<?php

namespace App\Services\Administration\Booking\DiningRoomBooking;

use Carbon\Carbon;
use App\Models\Booking\DiningRoomBooking\DiningRoomBooking;

class DiningRoomBookingService
{
    public function getAvailableTimeSlots($user, $today)
    {
        // Get shift timings
        $userShift = $user->current_shift;
        $shiftStart = Carbon::parse($userShift->start_time);
        $shiftEnd = Carbon::parse($userShift->end_time);

        // Time slots
        $allTimeSlots = [
            '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00',
            '12:00:00', '12:30:00', '17:00:00', '17:30:00', '18:00:00', '18:30:00',
            '19:00:00', '19:30:00', '20:00:00', '20:30:00', '01:00:00', '01:30:00',
            '02:00:00', '02:30:00', '03:00:00', '03:30:00', '04:00:00', '04:30:00'
        ];

        // If the user's shift is from 11:00 PM to 07:00 AM, handle overnight shifts
        if ($shiftStart->isAfter($shiftEnd)) {
            $shiftEnd->addDay();
        }

        // Filter time slots based on the user's shift time range
        return array_filter($allTimeSlots, function ($time) use ($shiftStart, $shiftEnd) {
            $slotTime = Carbon::parse($time);

            if ($shiftStart->isAfter($shiftEnd)) {
                return $slotTime->between($shiftStart, Carbon::parse('11:59 PM')) || $slotTime->between(Carbon::parse('12:00 AM'), $shiftEnd);
            }

            return $slotTime->between($shiftStart, $shiftEnd);
        });
    }

    public function disableTimes(&$availableTimeSlots, $bookings, $userBooking, $today)
    {
        foreach ($availableTimeSlots as $index => $time) {
            // Determine if the booking time has passed
            $slotDate = $today;
            if (Carbon::parse($time)->hour < Carbon::parse('09:00:00')->hour) {
                $slotDate = Carbon::tomorrow()->toDateString();
            }

            $slotDateTime = Carbon::parse("$slotDate $time");
            $isPast = $slotDateTime->lt(now());

            // Get the count of bookings for the specific time, excluding the current user's booking
            $bookingCount = $bookings->where('booking_time', $time)
                ->where('status', '!=', 'Cancelled')
                ->where('user_id', '!=', auth()->id()) // Exclude the current user's booking
                ->count();

            // Disable the time if it is past or if there are 8 or more bookings (excluding the user's booking)
            if ($userBooking) {
                // If the user has already booked this time, keep it enabled
                if ($userBooking->booking_time == $time) {
                    $availableTimeSlots[$index] = [
                        'time' => $time,
                        'disabled' => false,
                        'user_has_booking' => true,
                    ];
                } else {
                    $availableTimeSlots[$index] = [
                        'time' => $time,
                        'disabled' => true,
                        'user_has_booking' => false,
                    ];
                }
            } else {
                // Disable time if it has 8 bookings or it's in the past
                $disabled = $isPast || $bookingCount >= 8;
                $availableTimeSlots[$index] = [
                    'time' => $time,
                    'disabled' => $disabled,
                    'user_has_booking' => false,
                ];
            }
        }
    }


    public function determineBookingDate($user, $bookingTime)
    {
        $shiftStart = Carbon::parse($user->current_shift->start_time);
        $shiftEnd = Carbon::parse($user->current_shift->end_time);
        $slotTimeCarbon = Carbon::parse($bookingTime);
        $bookingDate = now()->toDateString();

        if ($shiftStart->isAfter($shiftEnd) && $slotTimeCarbon->hour < $shiftStart->hour) {
            $bookingDate = Carbon::tomorrow()->toDateString();
        }

        return $bookingDate;
    }

    public function checkExistingBooking($user, $bookingDate, $bookingTime)
    {
        return DiningRoomBooking::where('user_id', $user->id)
                                ->whereDate('booking_date', $bookingDate)
                                ->whereTime('booking_time', $bookingTime)
                                ->where('status', '!=', 'Cancelled')
                                ->first();
    }

    public function isSlotFull($bookingDate, $bookingTime)
    {
        $slotBookings = DiningRoomBooking::whereDate('booking_date', $bookingDate)
                            ->where('booking_time', $bookingTime)
                            ->where('status', '!=', 'Cancelled')
                            ->count();

        return $slotBookings >= 8;
    }

    public function createBooking($user, $bookingDate, $bookingTime)
    {
        DiningRoomBooking::create([
            'user_id'           => $user->id,
            'employee_shift_id' => $user->current_shift->id,
            'booking_date'      => $bookingDate,
            'booking_time'      => $bookingTime,
        ]);
    }

    public function cancelBooking($existingBooking)
    {
        $existingBooking->update(['status' => 'Cancelled']);
    }
}
