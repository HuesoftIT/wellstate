<?php

namespace App\Observers;

use App\Models\Slide;
use Illuminate\Support\Facades\Storage;

class SlideObserver
{
    /**
     * Trước khi tạo
     */
    public function creating(Slide $slide)
    {
        if (request()->hasFile('image')) {
            $slide->image = Slide::uploadAndResize(
                request()->file('image'),
                1920
            );
        }
    }

    /**
     * Trước khi update
     */
    public function updating(Slide $slide)
    {
        if (request()->hasFile('image')) {

            // ❌ Xóa ảnh cũ
            if (
                $slide->getOriginal('image') &&
                Storage::disk('public')->exists($slide->getOriginal('image'))
            ) {
                Storage::disk('public')->delete($slide->getOriginal('image'));
            }

            // ✅ Upload ảnh mới
            $slide->image = Slide::uploadAndResize(
                request()->file('image'),
                1920
            );
        }
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDeleted(Slide $slide)
    {
        if ($slide->image && Storage::disk('public')->exists($slide->image)) {
            Storage::disk('public')->delete($slide->image);
        }
    }
}
