<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Str;

class ServiceObserver
{
    public function creating(Service $service)
    {
        if (empty($service->slug)) {
            $service->slug = $this->generateSlugWithCategory($service);
        }
        if (request()->hasFile('image')) {
            $service->image = Service::uploadAndResize(
                request()->file('image')
            );
        }
    }

    public function updating(Service $service)
    {
        if (empty($service->slug)) {
            $service->slug = $this->generateSlugWithCategory($service);
        }
        if (request()->hasFile('image')) {

            // Xóa ảnh cũ
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->image = Service::uploadAndResize(
                request()->file('image')
            );
        }
    }

    protected function generateSlugWithCategory(Service $service): string
    {
        $serviceSlug  = Str::slug($service->name);
        $categorySlug = optional($service->serviceCategory)->slug;

        return trim("{$serviceSlug}-{$categorySlug}", '-');
    }


    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the Service "restored" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     *
     * @param  \App\Models\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}
