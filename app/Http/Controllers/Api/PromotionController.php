<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\Promotion\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __construct(
        protected PromotionService $promotionService
    ) {}
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
    public function apply(Request $request) {
        $discount_code = $request->discount_code;
        $services = $request->services;
        $room_fee = $request->room_fee;
        $subtotal = $request->subtotal;

        $booking = Booking::findOrFail(41);
       

        return $this->promotionService->apply($discount_code, $booking, $subtotal);
    }
}
