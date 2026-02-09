<?php

namespace App\Observers;

use App\Models\RoomType;

class RoomTypeObserver
{
    public function creating(RoomType $roomType)
    {
        if (! $roomType->code) {
            $nextId = RoomType::max('id') + 1;

            $roomType->code = 'RT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }
    }
    
    public function updating(RoomType $roomType)
    {
        if ($roomType->isDirty('code')) {
            $roomType->code = $roomType->getOriginal('code');
        }
    }


    /**
     * Handle the RoomType "updated" event.
     *
     * @param  \App\Models\RoomType  $roomType
     * @return void
     */
    public function updated(RoomType $roomType)
    {
        //
    }

    /**
     * Handle the RoomType "deleted" event.
     *
     * @param  \App\Models\RoomType  $roomType
     * @return void
     */
    public function deleted(RoomType $roomType)
    {
        //
    }

    /**
     * Handle the RoomType "restored" event.
     *
     * @param  \App\Models\RoomType  $roomType
     * @return void
     */
    public function restored(RoomType $roomType)
    {
        //
    }

    /**
     * Handle the RoomType "force deleted" event.
     *
     * @param  \App\Models\RoomType  $roomType
     * @return void
     */
    public function forceDeleted(RoomType $roomType)
    {
        //
    }
}
