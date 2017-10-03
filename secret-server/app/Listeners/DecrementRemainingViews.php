<?php

namespace App\Listeners;

use App\Events\SecretViewedEvent;

class DecrementRemainingViews
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SecretViewedEvent  $event
     * @return void
     */
    public function handle(SecretViewedEvent $event)
    {
        $event->secret->decrement('remainingViews');
    }
}
