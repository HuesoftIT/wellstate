<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingDTO
{
    public $customerId;
    public $membershipId;
    public $branchRoomTypeId;
    public $bookingDate;
    public $totalGuests;
    public $subtotal;
    public $services;
    public $phone;
    public $applyScope;

    public function __construct(
        $customerId,
        $membershipId,
        $branchRoomTypeId,
        $bookingDate,
        $totalGuests,
        $subtotal,
        $services = array(),
        $phone = null,
        $applyScope = null
    ) {
        $this->customerId = $customerId;
        $this->membershipId = $membershipId;
        $this->branchRoomTypeId = $branchRoomTypeId;
        $this->bookingDate = $this->normalizeDate($bookingDate);
        $this->totalGuests = $totalGuests;
        $this->subtotal = $subtotal;
        $this->services = is_array($services) ? $services : array();
        $this->phone = $phone;
        $this->applyScope = $applyScope;
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            null,
            $request->membership_id,
            $request->branch_room_type_id,
            $request->booking_date,
            $request->total_guests ? $request->total_guests : $request->guest_count,
            $request->subtotal,
            $request->services ? $request->services : array(),
            $request->phone ? $request->phone : $request->booker_phone,
            $request->apply_scope
        );
    }

    private function normalizeDate($date)
    {
        if ($date instanceof Carbon) {
            return $date->startOfDay();
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return Carbon::createFromFormat('d/m/Y', $date)->startOfDay();
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        }

        throw new \InvalidArgumentException('Invalid booking date format');
    }
}
