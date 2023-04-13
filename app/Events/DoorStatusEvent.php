<?php

namespace App\Events;

use App\Models\Office;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorStatusEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id)
    {
        $this->office_id = $office_id;
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
        return 'door-status';
    }
}
