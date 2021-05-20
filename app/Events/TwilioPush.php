<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TwilioPush {

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $user;
    public $pushTitle;
    public $pushBody;
    public $pushData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $pushTitle, $pushBody, $pushData, $userType) {
        $this->user = $user; 
        $this->pushTitle = $pushTitle;
        $this->pushBody = $pushBody;
        $this->pushData = $pushData;
        $this->userType = $userType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        //return new PrivateChannel('channel-name');
        return [];
    }

}
