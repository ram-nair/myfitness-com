<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TwilioRegister {

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $user;
    public $bindingType;
    public $userType;
    public $deleteBinding;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $bindingType, $userType, $deleteBinding=false) {
        $this->user = $user;
        $this->bindingType = $bindingType;
        $this->userType = $userType;
        $this->deleteBinding = $deleteBinding;
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
