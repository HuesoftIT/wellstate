<?php

namespace App\DTO;

class BookingDTO
{
    public function __construct(
        public ?int $customerId,
        public ?int $membershipId,
        public int $branchId,
        public ?int $branchRoomTypeId,
        public string $bookingDate,
        public int $totalGuests,
        public float $subtotal,
        // public int $promotionId,
        public array $serviceIds = [],
    ) {}

    // public static function fromRequest(array $data): self
    // {
    //     return new self(
    //         customerId: $data['customer_id'] ?? null,
    //         membershipId: $data['membership_id'] ?? null,
    //         branchId: $data['branch_id'],
    //         branchRoomTypeId: $data['branch_room_type_id'] ?? null,
    //         bookingDate: $data['booking_date'],
    //         // promotionId: $data['promotion_id'],
    //         totalGuests: $data['total_guests'],
    //         subtotal: $data['subtotal'] ?? 0,
    //         serviceIds: $data['service_ids'] ?? [],
    //     );
    // }
}
