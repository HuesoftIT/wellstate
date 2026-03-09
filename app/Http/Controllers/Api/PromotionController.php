<?php

namespace App\Http\Controllers\Api;

use App\DTO\BookingDTO;
use App\Http\Controllers\Controller;
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
        $subtotal = $request->subtotal;
        $branch_id = $request->branch_id;
        $room_type_id = $request->room_type_id;
        $booking_date = $request->booking_date;
        $total_guests = $request->total_guests;
        $phone = $request->phone;

        $bookingDTO = new BookingDTO(null, null,  $branch_id, $room_type_id, $booking_date, $total_guests, $subtotal, $services, $phone);

     
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
