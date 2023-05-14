<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $door_name, $office_id, $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $door_name, $message)
    {
        $this->office_id = $office_id;
        $this->door_name = $door_name;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('office.' . $this->office_id);
    }

    public function broadcastAs()
    {
        return 'door-alert';
    }

    public function broadcastWith()
    {
        return [
            'name' => $this->door_name,
            'message' => $this->message,
        ];
    }
}
