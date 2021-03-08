<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComprobanteRegistrado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $documento;
    public $serie; 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($documento,$serie)
    {
        $this->documento = $documento;
        $this->serie = $serie;
    }

}
