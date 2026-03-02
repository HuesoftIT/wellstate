<?php

namespace App\DTO;

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
}
