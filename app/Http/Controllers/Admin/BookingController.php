<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\BranchRoomType;
use App\Models\RoomType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\{
    Booking,
    BookingGuest,
    BookingGuestService,
    BookingGuestServiceRoom,
    Service,
    Promotion
};
use App\Services\BookingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Promotion\PromotionService;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PromotionService $promotionService
    ) {}
    public function booking(Request $request)
    {
        DB::transaction(function () use ($request) {
            //1. Count time
            [$startTime, $endTime, $maxDuration] = $this->bookingService->calculateBookingTime($request);

            //2. Create booking
            $booking = $this->bookingService->createBookingSkeleton($request, $startTime, $endTime, $maxDuration);

           

            //3. Create GUEST + SERVICE + ROOM
            [$subtotal, $totalDuration] = $this->bookingService->createGuestsAndServices($booking, $request, $startTime);
            
            //4. apply promotion
            $promotionResult = $this->promotionService->apply($request->discount_code, $booking, $subtotal);
            
            //5. complete booking
            $this->bookingService->finalizeBooking($booking, $subtotal, $totalDuration, $promotionResult);
        });

        return redirect()
            ->route('page.booking')
            ->with('success_booking', 'Đặt lịch thành công!');
    }


    public function index(Request $request)
    {
        $query = Booking::with([
            'customer',
            'guests',
            'branch'
        ]);

        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%' . $request->search . '%')
                ->where('booker_name', 'like', '%' . $request->search . '%')
                ->where('booker_phone', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query
            ->orderByDesc('booking_date')
            ->orderBy('start_time')
            ->paginate(15);
        $branches = Branch::active()->pluck('name', 'id');

        return view('admin.bookings.index', compact('bookings', 'branches'));
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Đã huỷ booking');
    }

    public function confirm(Booking $booking)
    {
        $booking->update([
            'status' => 'confirmed'
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Đã xác nhận booking');
    }


    public function show($id)
    {
        $booking = Booking::with([
            'branch',
            'customer',
            'guests.services.rooms',
            'guests.services.service',
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Form chỉnh sửa chi nhánh
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);

        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Cập nhật chi nhánh
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:paid,unpaid',
            'note' => 'nullable|string',
        ]);

        $booking->update($data);

        Alert::success('Cập nhật booking thành công');

        return redirect()->route('admin.bookings.show', $booking->id);
    }


    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'completed') {
            Alert::error('Không thể huỷ booking đã hoàn thành');
            return back();
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        $booking->delete();

        Alert::success('Đã huỷ booking');

        return redirect()->route('admin.bookings.index');
    }
}
