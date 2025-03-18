<?php

namespace App\Http\Controllers\Administration\Booking\DiningRoomBooking;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking\DiningRoomBooking\DiningRoomBooking;

class DiningRoomBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the current shift of the authenticated user
        $userShift = auth()->user()->current_shift;

        // Convert shift start and end times to Carbon for easy comparison
        $shiftStart = Carbon::parse($userShift->start_time);
        $shiftEnd = Carbon::parse($userShift->end_time);

        // List of all available time slots
        $allTimeSlots = [
            '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00',
            '12:00:00', '12:30:00', '17:00:00', '17:30:00', '18:00:00', '18:30:00',
            '19:00:00', '19:30:00', '20:00:00', '20:30:00', '01:00:00', '01:30:00',
            '02:00:00', '02:30:00', '03:00:00', '03:30:00', '04:00:00', '04:30:00'
        ];

        // If the user's shift is from 11:00 PM to 07:00 AM, we need to adjust the time range
        if ($shiftStart->isAfter($shiftEnd)) {
            // Handle overnight shifts (e.g., 11:00 PM - 07:00 AM)
            $shiftEnd->addDay(); // Add a day to end time to handle the midnight shift
        }

        // Filter time slots based on the user's shift time range
        $availableTimeSlots = array_filter($allTimeSlots, function ($time) use ($shiftStart, $shiftEnd) {
            $slotTime = Carbon::parse($time);

            // Handle overnight shifts (filter only if within the shift)
            if ($shiftStart->isAfter($shiftEnd)) {
                return $slotTime->between($shiftStart, Carbon::parse('11:59 PM')) || $slotTime->between(Carbon::parse('12:00 AM'), $shiftEnd);
            }

            // Regular shift handling (within a single day)
            return $slotTime->between($shiftStart, $shiftEnd);
        });

        // Get today's date
        $today = now()->toDateString();

        // Fetch bookings for today
        $bookings = DiningRoomBooking::whereDate('booking_date', $today)
            ->with('user')
            ->orderBy('booking_time', 'desc')
            ->get();

        // Get the authenticated user's existing booking for today
        $userBooking = DiningRoomBooking::where('user_id', auth()->id())
            ->whereDate('booking_date', $today)
            ->where('status', '!=', 'Cancelled')
            ->first();

        // Disable times where there are already 6 bookings or the time is already past
        foreach ($availableTimeSlots as $index => $time) {
            $slotDate = $today;
            if ($shiftStart->isAfter($shiftEnd) && Carbon::parse($time)->hour < $shiftStart->hour) {
                $slotDate = Carbon::tomorrow()->toDateString();
            }

            $slotDateTime = Carbon::parse("$slotDate $time");
            $isPast = $slotDateTime->lt(now());
            $bookingCount = $bookings->where('booking_time', $time)->where('status', '!=', 'Cancelled')->count();

            if ($userBooking) {
                // If user has a booking, only their booked time is active & highlighted
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
                // No existing booking — normal logic
                $disabled = $isPast || $bookingCount >= 6;
                $availableTimeSlots[$index] = [
                    'time' => $time,
                    'disabled' => $disabled,
                    'user_has_booking' => false,
                ];
            }
        }

        return view('administration.booking.dining_room_booking.index', compact('availableTimeSlots', 'bookings', 'userBooking'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bookOrCancel(Request $request)
    {
        $request->validate([
            'booking_time' => 'required|string',
        ]);

        $user = auth()->user();
        $bookingTime = Carbon::parse($request->input('booking_time'))->format('H:i:s');

        $shift = $user->current_shift;
        $shiftStart = Carbon::parse($shift->start_time);
        $shiftEnd = Carbon::parse($shift->end_time);

        // Determine the correct booking date based on overnight shift logic
        $bookingDate = now()->toDateString();
        $slotTimeCarbon = Carbon::parse($bookingTime);

        if ($shiftStart->isAfter($shiftEnd) && $slotTimeCarbon->hour < $shiftStart->hour) {
            // Time belongs to next day if overnight shift
            $bookingDate = Carbon::tomorrow()->toDateString();
        }

        // Check if the user already has a booking for this date
        $existingBooking = DiningRoomBooking::where('user_id', $user->id)
                                            ->whereDate('booking_date', $bookingDate)
                                            ->whereTime('booking_time', $bookingTime)
                                            ->where('status', '!=', 'Cancelled')
                                            ->first();

        if ($existingBooking) {
            $existingBooking->update([
                'status' => 'Cancelled'
            ]);

            toast('Table Booking Cancelled Successfully For '. show_time($request->booking_time, 'h:i A'), 'success');
            return redirect()->back();
        }

        // Check if the selected slot already has 6 bookings
        $slotBookings = DiningRoomBooking::whereDate('booking_date', $bookingDate)
                            ->where('booking_time', $bookingTime)
                            ->where('status', '!=', 'Cancelled')
                            ->count();

        if ($slotBookings >= 6) {
            return back()->with('error', 'This time slot is already fully booked.');
        }

        // Create a new booking record
        $booking = new DiningRoomBooking([
            'user_id'           => $user->id,
            'employee_shift_id' => $shift->id,
            'booking_date'      => $bookingDate,
            'booking_time'      => $bookingTime,
        ]);
        $booking->save();

        toast('Table Booked Successfully.', 'success');
        return redirect()->back();
    }
}
