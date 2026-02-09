<?php

namespace App\Observers;

use Illuminate\Support\Facades\Storage;
use App\Models\Promotion;
use Str;

class PromotionObserver
{
    /**
     * Handle the Promotion "created" event.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return void
     */
    public function creating(Promotion $promotion)
    {
        if (empty($promotion->discount_code)) {
            $promotion->discount_code = $this->generateUniqueSlug($promotion->name);
        }

        if (request()->hasFile('image')) {
            $promotion->image = Promotion::uploadAndResize(
                request()->file('image')
            );
        }
    }

    /**
     * Handle the Promotion "updated" event.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return void
     */
    public function updating(Promotion $promotion)
    {
        // Nếu đổi tên → đổi slug
        if ($promotion->isDirty('name')) {
            $promotion->discount_code = $this->generateUniqueSlug(
                $promotion->name,
                $promotion->id
            );
        }

        // Upload image mới
        if (request()->hasFile('image')) {

            if ($promotion->getOriginal('image')) {
                Storage::disk('public')->delete(
                    $promotion->getOriginal('image')
                );
            }

            $promotion->image = Promotion::uploadAndResize(
                request()->file('image')
            );
        }
    }

    protected function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $discount_code = Str::slug($name);
        $originalSlug = $discount_code;

        $i = 1;

        while (
            Promotion::where('discount_code', $discount_code)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $discount_code = $originalSlug . '-' . $i++;
        }

        return $discount_code;
    }


    /**
     * Handle the Promotion "deleted" event.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return void
     */
    public function deleted(Promotion $promotion)
    {
        //
    }

    /**
     * Handle the Promotion "restored" event.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return void
     */
    public function restored(Promotion $promotion)
    {
        //
    }

    /**
     * Handle the Promotion "force deleted" event.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return void
     */
    public function forceDeleted(Promotion $promotion)
    {
        //
    }
}
