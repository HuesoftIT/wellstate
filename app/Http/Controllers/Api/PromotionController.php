<?php

namespace App\Http\Controllers\Api;

use App\DTO\BookingDTO;
use App\Http\Controllers\Controller;
use App\Models\BranchRoomType;
use App\Models\Customer;
use App\Services\Promotion\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }
    /*
    {
        "discount_code": "khuyen-mai",
        "room_fee": 10000,
        "subtotal": 5300000,
        "services": [16,17],
        "membership_id": 1,
        "customer_id": 8,
        "booking_date": "2026-02-07"
    }
    */
    public function apply(Request $request)
    {

        $discount_code = $request->discount_code;
        $services = $request->services;
        $branch_id = $request->branch_id;
        $room_type_id = $request->room_type_id;
        $booking_date = $request->booking_date;
        $total_guests = $request->total_guests;
        $phone = $request->phone;
        $apply_scope = $request->apply_scope;
        $customer = Customer::active()->where('phone', $phone)->first();
        $customerId = $customer ? $customer->id : null;

        $branchRoomType = BranchRoomType::active()
            ->where('branch_id', $branch_id)
            ->where('room_type_id', $room_type_id)
            ->first();

        $branchRoomTypeId = $branchRoomType->id;
        $roomPrice = $branchRoomType->price;

        $subtotal = $request->subtotal + $roomPrice;
        $bookingDTO = new BookingDTO($customerId, null, $branchRoomTypeId, $booking_date, $total_guests, $subtotal, $services, $phone, $apply_scope);


        return $this->promotionService->apply($discount_code, $bookingDTO);
    }

    public function check(Request $request)
    {
        $bookingData = BookingDTO::fromRequest($request);

        $promotions = app(PromotionService::class)
            ->getAvailablePromotions($bookingData);

        return response()->json($promotions);
    }
}
