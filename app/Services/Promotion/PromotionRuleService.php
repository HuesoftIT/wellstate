<?php

namespace App\Services\Promotion;

use App\Models\Promotion;
use App\Models\PromotionRule;

class PromotionRuleService
{
    /**
     * Đồng bộ rules cho promotion
     */
    public function syncRules(Promotion $promotion, array $rules): void
    {
        $promotion->rules()->delete();

        foreach ($rules as $rule) {
            $promotion->rules()->create([
                'type'   => $rule['type'],
                'config' => $rule['config'] ?? null,
            ]);
        }
    }
}
