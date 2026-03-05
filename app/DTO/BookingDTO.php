<?php

namespace App\DTO;

use Illuminate\Http\Request;


class BookingDTO
{
    public $customerId;
    public $membershipId;
    public $branchId;
    public $branchRoomTypeId;
    public $bookingDate;
    public $totalGuests;
    public $subtotal;
    public $services;
    public $phone;

    public function __construct(
        $customerId,
        $membershipId,
        $branchId,
        $branchRoomTypeId,
        $bookingDate,
        $totalGuests,
        $subtotal,
        $services = [],
        $phone,
    ) {
        $this->customerId = $customerId;
        $this->membershipId = $membershipId;
        $this->branchId = $branchId;
        $this->branchRoomTypeId = $branchRoomTypeId;
        $this->bookingDate = $bookingDate;
        $this->totalGuests = $totalGuests;
        $this->subtotal = $subtotal;
        $this->services = $services;
        $this->phone = $phone;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            customerId: null,
            membershipId: $request->membership_id,
            branchId: $request->branch_id,
            branchRoomTypeId: $request->room_type_id,
            bookingDate: $request->booking_date,
            totalGuests: $request->guest_count,
            subtotal: $request->subtotal,
            services: $request->services ?? [],
            phone: $request->booker_phone,
        );
    }
}
