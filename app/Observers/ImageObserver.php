<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageObserver
{
    /**
     * Khi tạo image
     */
    public function creating(Image $image)
    {
        if ($image->image instanceof UploadedFile) {

            $image->image = Image::uploadAndResize(
                $image->image,
                450
            );
        }
    }

    /**
     * Khi update image
     */
    public function updating(Image $image)
    {
        if ($image->image instanceof UploadedFile) {

            if ($image->getOriginal('image')) {
                Storage::disk('public')->delete($image->getOriginal('image'));
            }

            $image->image = Image::uploadAndResize(
                $image->image,
                450
            );
        }
    }

    /**
     * Khi delete
     */
    public function deleting(Image $image)
    {
        if ($image->isForceDeleting() && $image->image) {

            Storage::disk('public')->delete($image->image);
        }
    }
}
