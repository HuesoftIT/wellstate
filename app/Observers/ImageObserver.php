<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Khi tạo mới image
     */
    public function creating(Image $image)
    {
        if (request()->hasFile('image')) {
            $image->image = Image::uploadAndResize(
                request()->file('image'),
                450
            );
        }
    }

    /**
     * Khi cập nhật image
     */
    public function updating(Image $image)
    {
        if (request()->hasFile('image')) {

            // Xóa ảnh cũ nếu có
            if ($image->getOriginal('image')) {
                Storage::disk('public')->delete($image->getOriginal('image'));
            }

            // Upload ảnh mới
            $image->image = Image::uploadAndResize(
                request()->file('image'),
                450
            );
        }
    }

    /**
     * Khi xóa mềm
     */
    public function deleting(Image $image)
    {
        // Chỉ xóa file khi force delete
        if ($image->isForceDeleting() && $image->image) {
            Storage::disk('public')->delete($image->image);
        }
    }

    /**
     * Khi khôi phục
     */
    public function restored(Image $image)
    {
        // Nếu cần xử lý gì khi restore thì thêm ở đây
    }

    /**
     * Khi xóa vĩnh viễn
     */
    public function forceDeleted(Image $image)
    {
        // Đã xử lý trong deleting()
    }
}
