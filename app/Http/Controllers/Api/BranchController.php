<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\BranchRoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    //

    public function getAvailableTimes(Request $request)
    {
        $branch = Branch::findOrFail($request->branch_id);

        $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $roomTypeId = $request->room_type_id;

        $branchRoomType = BranchRoomType::where('branch_id', $branch->id)
            ->where('room_type_id', $roomTypeId)
            ->firstOrFail();

        $capacity = $branchRoomType->capacity;

        $open  = $branch->open_time->copy();
        $close = $branch->close_time->copy();

        $step = 15;

        $slots = [];
        $disabled = [];

        $bookings = Booking::where('branch_room_type_id', $branchRoomType->id)
            ->whereDate('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get();

        while ($open <= $close) {

            $slotTime = $open->format('H:i');

            $guestCount = 0;

            foreach ($bookings as $booking) {

                if (
                    $slotTime >= substr($booking->start_time, 0, 5) &&
                    $slotTime < substr($booking->end_time, 0, 5)
                ) {
                    $guestCount += $booking->total_guests;
                }
            }

            if ($guestCount >= $capacity) {
                $disabled[] = $slotTime;
            }

            $slots[] = $slotTime;

            $open->addMinutes($step);
        }

        return response()->json([
            'open_time' => $branch->open_time->format('H:i'),
            'close_time' => $branch->close_time->format('H:i'),
            'disabled_times' => $disabled
        ]);
    }
}
