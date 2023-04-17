<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorScheduleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id, $door_id, $status, $time_end;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $door_id, $status, $time_end)
    {
        $this->office_id = $office_id;
        $this->door_id = $door_id;
        $this->status = $status;
        $this->time_end = $time_end;
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
        return 'door-scedule-command';
    }

    public function broadcastWith()
    {
        return [
            'door_id' => $this->door_id,
            'status' => $this->status,
            'time_end' => $this->time_end,
        ];
    }
}
