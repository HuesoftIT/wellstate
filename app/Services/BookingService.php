<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\BookingGuestService;
use App\Models\BookingGuestServiceRoom;
use App\Models\BranchRoomType;
use App\Models\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Str;

class BookingService
{
    public function calculateBookingTime(Request $request)
    {
        if (!$request->booking_date || !$request->booking_time) {
            throw new Exception("Ngày, giờ booking là bắt buộc");
        }


        $maxDuration = 0;

        foreach ($request->guests as $guest) {
            $duration = Service::active()->whereIn(
                'id',
                collect($guest['services'])->pluck('service_id')
            )->sum('duration');

            $maxDuration = max($maxDuration, $duration);
        }

        $startTime = Carbon::createFromFormat('H:i', $request->booking_time);
        $endTime   = $startTime->copy()->addMinutes($maxDuration);

        return [$startTime, $endTime, $maxDuration];
    }

    public function createBookingSkeleton(Request $request, Carbon $startTime, Carbon $endTime, int $maxDuration): Booking
    {
        $branchRoomType = BranchRoomType::active()
            ->where('branch_id', $request->branch_id)
            ->where('room_type_id', $request->room_type_id)
            ->first();
        return Booking::create([
            'booking_code'   => $this->generateBookingCode(),
            'customer_id' => auth('customer')->check()
                ? auth('customer')->id()
                : null,

            'branch_id'      => $request->branch_id,
            'room_type_id'   => $request->room_type_id,
            'branch_room_type_id' => optional($branchRoomType)->id,

            'booker_name'    => $request->booker_name,
            'booker_phone'   => $request->booker_phone,

            'booking_date'   => Carbon::createFromFormat(
                'd/m/Y',
                $request->booking_date
            )->format('Y-m-d'),

            'start_time'     => $startTime->format('H:i'),
            'end_time'       => $endTime->format('H:i'),

            'total_guests'   => count($request->guests),
            'total_duration' => $maxDuration,


            'subtotal_amount' => 0,
            'discount_amount' => 0,
            'total_amount'    => 0,

            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'note'           => $request->note,
        ]);
    }

    protected function generateBookingCode()
    {
        return 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function createGuestsAndServices(
        Booking $booking,
        Request $request,
        Carbon $startTime
    ): array {
        $subtotal = 0;
        $totalDuration = 0;

        foreach ($request->guests as $guestData) {

            $guest = BookingGuest::create([
                'booking_id' => $booking->id,
                'guest_name' => $guestData['name'],
            ]);

            foreach ($guestData['services'] as $serviceData) {

                $service = Service::findOrFail($serviceData['service_id']);

                $guestService = BookingGuestService::create([
                    'booking_id'       => $booking->id,
                    'booking_guest_id' => $guest->id,
                    'service_id'       => $service->id,
                    'service_name'     => $service->title,
                    'duration'         => $service->duration,
                    'quantity'         => 1,
                    'price'            => $service->sale_price ? $service->sale_price : $service->price,
                    'total_price'      => $service->sale_price ? $service->sale_price : $service->price,
                    'start_time'       => $startTime,
                    'end_time'         => $startTime->copy()->addMinutes($service->duration),
                    'status'           => 'pending',
                ]);

                $roomType = BranchRoomType::active()->where('branch_id', $request->branch_id)
                    ->where('room_type_id', $request->room_type_id)
                    ->firstOrFail();

                BookingGuestServiceRoom::create([
                    'booking_guest_service_id' => $guestService->id,
                    'branch_room_type_id'      => $roomType->id,
                    'room_type_name'           => $roomType->roomType->name,
                    'quantity'                 => 1,
                    'price'                    => $roomType->price,
                ]);

                $price = $service->sale_price ?: $service->price;

                $subtotal += $price;
                $totalDuration += $service->duration;
            }
        }
        $subtotal += $roomType->price;

        return [$subtotal, $totalDuration];
    }

    public function finalizeBooking(
        Booking $booking,
        float $subtotal,
        int $totalDuration,
        ?array $promotionResult
    ): void {
        $promotion = isset($promotionResult['promotion']) ? $promotionResult['promotion'] : null;
        $discount  = isset($promotionResult['discount']) ? $promotionResult['discount'] : 0;

        $booking->update([
            'promotion_id'    => $promotion ? $promotion->id : null,
            'discount_code'   => $promotion ? $promotion->code : null,
            'total_duration'  => $totalDuration,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discount,
            'total_amount'    => max(0, $subtotal - $discount),
        ]);
    }
}
