<?php
namespace App\Events;

use App\Secret;

class SecretViewedEvent extends Event
{

    public $secret;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }
}
