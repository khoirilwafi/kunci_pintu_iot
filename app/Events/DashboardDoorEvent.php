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

    protected $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($const_name)
    {
        $this->name = $const_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private.dashboard.1feee18f-2bea-e75-9512-7cbeb0989f27');
    }

    public function broadcastAs()
    {
        return 'door-event';
    }

    public function broadcastWith()
    {
        return array(
            'id' => $this->name,
            'message' => 'success'
        );
    }
}
