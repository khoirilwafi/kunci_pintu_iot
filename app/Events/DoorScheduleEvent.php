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

    protected $office_id, $device_id, $token, $time_end;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $device_id, $token, $time_end)
    {
        $this->office_id = $office_id;
        $this->device_id = $device_id;
        $this->token = $token;
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
            'device_id' => $this->device_id,
            'token' => $this->token,
            'time_end' => $this->time_end,
        ];
    }
}
