<?php

namespace App\Services\Promotion;

use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionRule;
use Carbon\Carbon;
use Exception;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class PromotionService
{
    /**
     * ENTRY POINT
     */
    public function apply(?string $code, Booking $booking, float $subtotal): array
    {
        if (empty($code)) {
            return $this->emptyResult();
        }

        $promotion = $this->findValidPromotion($code);

        $this->validateBase($promotion, $booking, $subtotal);
        $this->validateRules($promotion, $booking);
        $this->validateUserUsage($promotion, $booking);

        $discount = $this->calculateDiscount($promotion, $subtotal);

        return [
            'promotion' => $promotion,
            'discount'  => $discount,
        ];
    }

    /**
     * ===== FIND & BASIC VALIDATE =====
     */
    protected function findValidPromotion(string $code): Promotion
    {
        $code = trim(mb_strtolower($code));
        $promotion = Promotion::active()->where('discount_code', $code)
            ->first();

        if (!$promotion) {
            throw new Exception('Mã khuyến mãi không hợp lệ');
        }

        if ($promotion->start_date && Carbon::now()->lt($promotion->start_date)) {
            throw new Exception('Mã khuyến mãi chưa bắt đầu');
        }

        if ($promotion->end_date && Carbon::now()->gt($promotion->end_date)) {
            throw new Exception('Mã khuyến mãi đã hết hạn');
        }

        return $promotion;
    }

    /**
     * ===== BASE CONDITION =====
     */
    protected function validateBase(
        Promotion $promotion,
        Booking $booking,
        float $subtotal
    ): void {
        if (
            $promotion->discount_min_order_value &&
            $subtotal < $promotion->discount_min_order_value
        ) {
            throw new Exception('Đơn hàng chưa đủ điều kiện áp dụng');
        }

        if (
            $promotion->discount_max_uses &&
            $promotion->discount_uses_count >= $promotion->discount_max_uses
        ) {
            throw new Exception('Mã khuyến mãi đã hết lượt sử dụng');
        }
    }

    /**
     * ===== PER USER LIMIT =====
     */
    protected function validateUserUsage(Promotion $promotion, Booking $booking): void
    {
        if (! $promotion->discount_max_uses_per_user || ! $booking->customer) {
            return;
        }

        $usedCount = $booking->customer
            ->bookings()
            ->where('promotion_id', $promotion->id)
            ->count();

        if ($usedCount >= $promotion->discount_max_uses_per_user) {
            throw new Exception('Bạn đã sử dụng mã khuyến mãi này tối đa');
        }
    }

    /**
     * ===== RULES =====
     */
    protected function validateRules(Promotion $promotion, Booking $booking): void
    {
        foreach ($promotion->rules()->get() as $rule) {
            if (! $this->checkRule($rule, $booking)) {
                throw new Exception('Mã khuyến mãi không thỏa điều kiện áp dụng');
            }
        }
    }

    protected function checkRule(PromotionRule $rule, Booking $booking): bool
    {
        $config = $rule->config ?? [];

        return match ($rule->type) {
            'service'    => $this->checkServiceRule($config, $booking),
            'membership' => $this->checkMembershipRule($config, $booking),
            'user'       => $this->checkUserRule($config, $booking),
            'birthday'   => $this->checkBirthdayRule($config, $booking),
            default      => true,
        };
    }

    /**
     * ===== SERVICE RULE =====
     */
    protected function checkServiceRule(array $config, Booking $booking): bool
    {
        $serviceIds = $booking->guestServices()
            ->pluck('service_id')
            ->unique()
            ->toArray();

        $ruleIds = $config['ids'] ?? [];
        $mode = $config['mode'] ?? 'only';

        return $mode === 'only'
            ? empty(array_diff($serviceIds, $ruleIds))
            : empty(array_intersect($serviceIds, $ruleIds));
    }

    /**
     * ===== MEMBERSHIP RULE =====
     */
    protected function checkMembershipRule(array $config, Booking $booking): bool
    {
        return $booking->customer &&
            in_array($booking->customer->membership_id, $config['ids'] ?? []);
    }

    /**
     * ===== USER RULE =====
     */
    protected function checkUserRule(array $config, Booking $booking): bool
    {
        if (! $booking->customer) {
            return false;
        }

        $mode = $config['mode'] ?? 'only';

        return $mode === 'only'
            ? in_array($booking->customer->id, $config['ids'] ?? [])
            : ! in_array($booking->customer->id, $config['ids'] ?? []);
    }

    /**
     * ===== BIRTHDAY RULE =====
     */
    protected function checkBirthdayRule(array $config, Booking $booking): bool
    {
        return !($config['enabled'] ?? false)
            || ($booking->customer?->birthday?->isBirthday() ?? false);
    }

    /**
     * ===== DISCOUNT =====
     */
    protected function calculateDiscount(Promotion $promotion, float $subtotal): float
    {
        $discount = $promotion->discount_type === 'percent'
            ? $subtotal * ($promotion->discount_value / 100)
            : $promotion->discount_value;

        if ($promotion->discount_max_value) {
            $discount = min($discount, $promotion->discount_max_value);
        }

        return round($discount, 2);
    }

    protected function emptyResult(): array
    {
        return ['promotion' => null, 'discount' => 0];
    }
}
