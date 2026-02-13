<?php

namespace App\Http\Controllers\Admin;

use App\DTO\BookingDTO;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\{
    Booking,
    Promotion
};
use App\Services\BookingService;
use Illuminate\Support\Facades\DB;
use App\Services\Promotion\PromotionService;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $bookingService;
    protected $promotionService;

    public function __construct(
        BookingService $bookingService,
        PromotionService $promotionService
    ) {
        $this->bookingService = $bookingService;
        $this->promotionService = $promotionService;
    }

    public function booking(Request $request)
    {
        DB::transaction(function () use ($request) {
            //1. Count time
            [$startTime, $endTime, $maxDuration] = $this->bookingService->calculateBookingTime($request);

            //2. Create booking
            $booking = $this->bookingService->createBookingSkeleton($request, $startTime, $endTime, $maxDuration);

            //3. Create GUEST + SERVICE + ROOM
            [$subtotal, $totalDuration] = $this->bookingService->createGuestsAndServices($booking, $request, $startTime);

            $customer = auth('customer')->user();

            $serviceIds = $booking->guests
                ->flatMap(function ($g) {
                    return $g->services->pluck('service_id');
                })
                ->toArray();

            $bookingDTO = new BookingDTO(
                $customer ? $customer->id : null,
                $customer ? $customer->membership_id : null,
                $booking->branch_id,
                $booking->room_type_id,
                $booking->booking_date,
                $booking->total_guests,
                $subtotal,
                $serviceIds
            );


            //4. apply promotion
            $promotionResult = $this->promotionService->apply($request->discount_code, $bookingDTO);

            //5. complete booking
            $this->bookingService->finalizeBooking($booking, $bookingDTO->subtotal, $totalDuration, $promotionResult);
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
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('booker_name', 'like', "%{$search}%")
                    ->orWhere('booker_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
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
            'branchRoomType',
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

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);

        // ❌ Chưa thanh toán thì không cho complete
        if ($booking->payment_status !== 'paid') {
            Alert::error('Booking chưa thanh toán, không thể hoàn thành');
            return back();
        }

        // ❌ Đã hoàn thành rồi
        if ($booking->status === 'completed') {
            Alert::warning('Booking đã được hoàn thành trước đó');
            return back();
        }

        // ❌ Booking bị huỷ
        if ($booking->status === 'cancelled') {
            Alert::error('Booking đã bị huỷ, không thể hoàn thành');
            return back();
        }

        $booking->update([
            'status' => 'completed',
        ]);

        Alert::success('Booking đã được hoàn thành');

        return redirect()->route('bookings.show', $booking->id);
    }


    public function confirmPayment($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->payment_status === 'paid') {
            Alert::warning('Booking này đã được thanh toán trước đó');
            return back();
        }

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            Alert::error('Không thể xác nhận thanh toán cho booking ở trạng thái này');
            return back();
        }

        // 2. Update payment
        $booking->update([
            'payment_status' => 'paid',
        ]);

        // (Optional) Nếu bạn có bảng payments sau này
        // $booking->payments()->create([
        //     'amount' => $booking->total_amount,
        //     'method' => 'cash', // hoặc transfer
        //     'confirmed_by' => Auth::id(),
        // ]);

        Alert::success('Đã xác nhận thanh toán booking');

        return redirect()->route('bookings.index');
    }
}
