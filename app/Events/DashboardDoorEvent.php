<?php

namespace App\Events;

use App\Models\Office;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardDoorEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id, $device_id, $socket_id, $is_lock;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $device_id, $socket_id, $is_lock)
    {
        $this->office_id = $office_id;
        $this->device_id = $device_id;
        $this->socket_id = $socket_id;
        $this->is_lock   = $is_lock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $office = Office::where('id', $this->office_id)->first();
        return new PrivateChannel('private.dashboard.' . $office->user_id);
    }

    public function broadcastAs()
    {
        return 'door-event';
    }

    public function broadcastWith()
    {
        return array(
            'device_id' => $this->device_id,
            'socket_id' => $this->socket_id,
            'is_lock'   => $this->is_lock,
        );
    }
}
