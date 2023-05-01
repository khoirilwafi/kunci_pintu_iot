<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorScheduleEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id, $user_id, $door_id, $key, $time_end, $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $user_id, $door_id, $time_end, $status, $key)
    {
        $this->office_id = $office_id;
        $this->user_id = $user_id;
        $this->door_id = $door_id;
        $this->key = $key;
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
            'device_id' => $this->door_id,
            'user_id' => $this->user_id,
            'time_end' => $this->time_end,
            'status' => $this->status,
            'key' => $this->key,
        ];
    }
}
