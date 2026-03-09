<?php

namespace App\Services\Promotion;

use App\DTO\BookingDTO;
use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionRule;
use App\Models\PromotionUsage;
use Carbon\Carbon;
use Exception;

class PromotionService
{


    public function getAvailablePromotions(BookingDTO $booking): array
    {
        $today = now()->toDateString();

        $promotions = Promotion::with('rules')
            ->where('is_active', 1)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            })
            ->where(function ($q) use ($booking) {
                $q->whereNull('discount_min_order_value')
                    ->orWhere('discount_min_order_value', '<=', $booking->subtotal);
            })
            ->get();

        $validPromotions = [];

        foreach ($promotions as $promotion) {

            try {

                // 🔥 tái sử dụng toàn bộ engine apply
                $this->validateBase($promotion, $booking);
                $this->validateRules($promotion, $booking);
                $this->validateUserUsage($promotion, $booking);

                $eligibleAmount = $this->getEligibleAmount($promotion, $booking);

                if ($eligibleAmount <= 0) {
                    continue;
                }

                $discount = $this->calculateDiscount($promotion, $eligibleAmount);

                $validPromotions[] = [
                    'id' => $promotion->id,
                    'title' => $promotion->title,
                    "discount_code" => $promotion->discount_code,
                    'discount_type' => $promotion->discount_type,
                    'discount_value' => $promotion->discount_value,
                    'eligible_amount' => $eligibleAmount,
                    'discount_amount' => $discount,
                    'final_total' => max(0, $booking->subtotal - $discount),
                ];
            } catch (\Throwable $e) {
                // ❌ Không làm gì cả
                // Promotion không hợp lệ thì bỏ qua
                continue;
            }
        }

        // Optional: sort theo discount lớn nhất
        usort($validPromotions, function ($a, $b) {
            return $b['discount_amount'] <=> $a['discount_amount'];
        });

        return $validPromotions;
    }
    /**
     * ENTRY POINT
     */
    public function apply($code, BookingDTO $booking)
    {
        if (empty($code)) {
            return $this->emptyResult();
        }
        $phone = $booking->phone;
        $promotion = $this->findValidPromotion($code, $phone);

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
    protected function findValidPromotion(string $code, $phone): Promotion
    {
        $code = trim($code);
        $promotion = Promotion::active()->where('discount_code', $code)
            ->first();
        if (!$phone) {
            throw new Exception('Số điện thoại là bắt buộc để áp dụng mã khuyến mãi');
        }

        if (!$promotion) {
            throw new Exception('Mã khuyến mãi không hợp lệ');
        }


        if ($promotion->start_date && Carbon::now()->lt($promotion->start_date)) {
            throw new Exception('Mã khuyến mãi chưa bắt đầu');
        }

        if ($promotion->end_date && Carbon::now()->gt($promotion->end_date)) {
            throw new Exception('Mã khuyến mãi đã hết hạn');
        }


        if ($promotion->discount_max_uses_per_user !== null) {

            $userUses = PromotionUsage::where('promotion_id', $promotion->id)
                ->where('phone_number', $phone)
                ->count();

            if ($userUses >= $promotion->discount_max_uses_per_user) {
                throw new Exception('Bạn đã sử dụng hết số lượt cho mã khuyến mãi này');
            }
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
    protected function calculateDiscount(Promotion $promotion, $eligibleAmount)
    {
        $discount = $promotion->discount_type === 'percent'
            ? $eligibleAmount * ($promotion->discount_value / 100)
            : $promotion->discount_value;

        if ($promotion->discount_max_value) {
            $discount = min($discount, $promotion->discount_max_value);
        }

        $discount = min($discount, $eligibleAmount);

        return round($discount, 2);
    }

    protected function emptyResult(): array
    {
        return ['promotion' => null, 'discount' => 0];
    }

    public function recordUsage(Promotion $promotion, Booking $booking, $discountAmount)
    {
        if (!$promotion) {
            return;
        }

        $promotion->increment('discount_uses_count');

        PromotionUsage::create([
            'promotion_id'   => $promotion->id,
            'phone_number'   => $booking->booker_phone,
            'booking_id'     => $booking->id,
            'discount_amount' => $discountAmount,
            'used_at'        => now(),
        ]);
    }
}
