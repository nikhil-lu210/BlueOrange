<?php

namespace App\Http\Controllers\Administration\Booking\DiningRoomBooking;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking\DiningRoomBooking\DiningRoomBooking;
use App\Services\Administration\Booking\DiningRoomBooking\DiningRoomBookingService;

class DiningRoomBookingController extends Controller
{
    protected $bookingService;

    public function __construct(DiningRoomBookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = now()->toDateString();

        // Get the authenticated user's booking for today (if any)
        $userBooking = $this->getUserBookingForToday($today);

        // Get all bookings for today, eager loading 'user' relationship for efficiency
        $bookings = $this->getBookingsForToday($today);

        // Get the available time slots for the authenticated user
        $availableTimeSlots = $this->bookingService->getAvailableTimeSlots(auth()->user(), $today);

        // Mark times as disabled or available
        $this->bookingService->disableTimes($availableTimeSlots, $bookings, $userBooking, $today);

        // Return the view with required data
        return view('administration.booking.dining_room_booking.index', compact('availableTimeSlots', 'bookings'));
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

        // Determine booking date
        $bookingDate = $this->bookingService->determineBookingDate($user, $bookingTime);

        // Check for existing booking
        $existingBooking = $this->bookingService->checkExistingBooking($user, $bookingDate, $bookingTime);

        if ($existingBooking) {
            $this->bookingService->cancelBooking($existingBooking);
            toast('Table Booking Cancelled Successfully For '. show_time($request->booking_time, 'h:i A'), 'success');
            return redirect()->back();
        }

        // Check if the time slot is available
        if ($this->bookingService->isSlotFull($bookingDate, $bookingTime)) {
            return back()->with('error', 'This time slot is already fully booked.');
        }

        // Create a new booking
        $this->bookingService->createBooking($user, $bookingDate, $bookingTime);

        toast('Table Booked Successfully.', 'success');
        return redirect()->back();
    }

    /**
     * Fetch the authenticated user's booking for today.
     *
     * @param string $today
     * @return \App\Models\DiningRoomBooking|null
     */
    protected function getUserBookingForToday(string $today)
    {
        return DiningRoomBooking::where('user_id', auth()->id())
            ->whereDate('booking_date', $today)
            ->where('status', '!=', 'Cancelled')
            ->first();
    }

    /**
     * Fetch all bookings for today.
     *
     * @param string $today
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getBookingsForToday(string $today)
    {
        return DiningRoomBooking::whereDate('booking_date', $today)
            ->with('user')  // Eager load the 'user' relationship to prevent N+1 query issue
            ->orderBy('booking_time', 'desc')
            ->get();
    }
}
