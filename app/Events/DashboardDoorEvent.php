<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardDoorEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $message, $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg, $id)
    {
        $this->message = $msg;
        $this->user_id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private.dashboard.', $this->user_id);
    }

    public function broadcastAs()
    {
        return 'door-event';
    }

    public function broadcastWith()
    {
        return 'hello';
    }
}
