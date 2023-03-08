<?php

namespace App\Observers;

use App\Models\Parking;

class ParkingObserver
{
    /**
     * Handle the Parking "created" event.
     *
     * @param  \App\Models\Parking  $parking
     * @return void
     */
    public function created(Parking $parking)
    {
        //
    }

    public function creating(Parking $parking)
    {
        if (auth()->check()) {
            $parking->user_id = auth()->id();
        }
        $parking->start_time = now();
    }

    /**
     * Handle the Parking "updated" event.
     *
     * @param  \App\Models\Parking  $parking
     * @return void
     */
    public function updated(Parking $parking)
    {
        //
    }

    /**
     * Handle the Parking "deleted" event.
     *
     * @param  \App\Models\Parking  $parking
     * @return void
     */
    public function deleted(Parking $parking)
    {
        //
    }

    /**
     * Handle the Parking "restored" event.
     *
     * @param  \App\Models\Parking  $parking
     * @return void
     */
    public function restored(Parking $parking)
    {
        //
    }

    /**
     * Handle the Parking "force deleted" event.
     *
     * @param  \App\Models\Parking  $parking
     * @return void
     */
    public function forceDeleted(Parking $parking)
    {
        //
    }
}
