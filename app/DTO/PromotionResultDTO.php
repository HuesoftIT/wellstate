<?php

namespace App\DTO;

use App\Models\Promotion;

class PromotionResultDTO
{
    public function __construct(
        public ?Promotion $promotion,
        public float $discount,
    ) {}

    public function toSnapshot(): array
    {
        if (! $this->promotion) {
            return [];
        }

        return [
            'id' => $this->promotion->id,
            'code' => $this->promotion->discount_code,
            'type' => $this->promotion->discount_type,
            'value' => $this->promotion->discount_value,
            'max_value' => $this->promotion->discount_max_value,
        ];
    }
}
