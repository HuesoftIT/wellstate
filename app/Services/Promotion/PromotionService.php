<?php

namespace App\Services\Promotion;

use App\DTO\BookingDTO;
use App\DTO\PromotionResultDTO;
use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionRule;
use Carbon\Carbon;
use Exception;

class PromotionService
{
    /**
     * ENTRY POINT
     */
    public function apply($code, BookingDTO $booking)
    {
        if (empty($code)) {
            return $this->emptyResult();
        }

        $promotion = $this->findValidPromotion($code);

        $this->validateBase($promotion, $booking);
        $this->validateRules($promotion, $booking);
        $this->validateUserUsage($promotion, $booking);

        $eligibleAmount = $this->getEligibleAmount($promotion, $booking);

        if ($eligibleAmount <= 0) {
            throw new Exception('Không có dịch vụ hợp lệ để áp dụng khuyến mãi');
        }
        $discount = $this->calculateDiscount($promotion, $eligibleAmount);

        return [
            'promotion' => $promotion,
            'discount'  => $discount,
            'eligible_amount' => $eligibleAmount,
        ];
    }
    protected function getEligibleAmount(Promotion $promotion, BookingDTO $booking)
    {
        $amount = 0;

        foreach ($promotion->rules as $rule) {
            if ($rule->type === 'service') {

                $config = $rule->config ?? [];
                $ruleIds = $config['ids'] ?? [];

                foreach ($booking->services as $service) {
                    if (in_array($service['id'], $ruleIds)) {
                        $amount += $service['price'];
                    }
                }
            }
        }

        // Nếu không có service rule -> áp cho toàn bộ booking
        if ($amount == 0) {
            return $booking->subtotal;
        }

        return $amount;
    }
    /**
     * ===== FIND & BASIC VALIDATE =====
     */
    protected function findValidPromotion(string $code): Promotion
    {
        $code = trim($code);
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
        BookingDTO $booking
    ) {
        if (
            $promotion->discount_min_order_value &&
            $booking->subtotal < $promotion->discount_min_order_value
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
    protected function validateUserUsage(Promotion $promotion, BookingDTO $booking)
    {
        if (! $promotion->discount_max_uses_per_user || ! $booking->customerId || !$booking->phone) {
            return;
        }

        $usedCount = Booking::active()
            ->where('customer_id', $booking->customerId)
            ->where('promotion_id', $promotion->id)
            ->count();

        if ($usedCount >= $promotion->discount_max_uses_per_user) {
            throw new Exception('Bạn đã sử dụng mã khuyến mãi này tối đa');
        }
    }

    /**
     * ===== RULES =====
     */
    protected function validateRules(Promotion $promotion, BookingDTO $booking)
    {
        foreach ($promotion->rules()->get() as $rule) {
            if (! $this->checkRule($rule, $booking)) {
                throw new Exception('Mã khuyến mãi không thỏa điều kiện áp dụng');
            }
        }
    }

    protected function checkRule(PromotionRule $rule, BookingDTO $booking)
    {
        $config = $rule->config ?? [];

        switch ($rule->type) {
            case 'service':
                return $this->checkServiceRule($config, $booking);

            case 'membership':
                return $this->checkMembershipRule($config, $booking);

            case 'user':
                return $this->checkUserRule($config, $booking);

            case 'birthday':
                return $this->checkBirthdayRule($config, $booking);

            default:
                return true;
        }
    }


    /**
     * ===== SERVICE RULE =====
     */
    protected function checkServiceRule(array $config, BookingDTO $booking)
    {
        $ruleIds = $config['ids'] ?? [];
        $mode = $config['mode'] ?? 'include';

        $bookingServiceIds = array_map('intval', array_column($booking->services, 'id'));
        $ruleIds = array_map('intval', $ruleIds);

        if ($mode === 'only') {
            return empty(array_diff($bookingServiceIds, $ruleIds));
        }

        return !empty(array_intersect($bookingServiceIds, $ruleIds));
    }

    /**
     * ===== MEMBERSHIP RULE =====
     */
    protected function checkMembershipRule(array $config, BookingDTO $booking)
    {
        if (! $booking->membershipId) {
            return false;
        }
        return in_array($booking->membershipId, $config['ids'] ?? []);
    }

    /**
     * ===== USER RULE =====
     */
    protected function checkUserRule(array $config, BookingDTO $booking)
    {
        if (! $booking->customerId) {
            return false;
        }
        $ids  = $config['ids'] ?? [];
        $mode = $config['mode'] ?? 'only';

        if (empty($ids)) {
            return true;
        }

        return $mode === 'only'
            ? in_array($booking->customerId, $config['ids'] ?? [])
            : ! in_array($booking->customerId, $config['ids'] ?? []);
    }

    /**
     * ===== BIRTHDAY RULE =====
     */
    protected function checkBirthdayRule(array $config, BookingDTO $booking)
    {
        return false;
    }

    /**
     * ===== DISCOUNT =====
     */
    protected function calculateDiscount(Promotion $promotion, $subtotal)
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
