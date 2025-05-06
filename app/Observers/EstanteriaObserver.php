<?php

namespace App\Observers;

use App\Models\Estanteria;

class EstanteriaObserver
{
    /**
     * Handle the Estanteria "created" event.
     */
    public function created(Estanteria $estanteria): void
    {
        //
    }

    /**
     * Handle the Estanteria "updated" event.
     */
    public function updated(Estanteria $estanteria): void
    {
        //
    }

    /**
     * Handle the Estanteria "deleted" event.
     */
    public function deleted(Estanteria $estanteria): void
    {
        //
    }

    /**
     * Handle the Estanteria "restored" event.
     */
    public function restored(Estanteria $estanteria): void
    {
        //
    }

    /**
     * Handle the Estanteria "force deleted" event.
     */
    public function forceDeleted(Estanteria $estanteria): void
    {
        //
    }
}
