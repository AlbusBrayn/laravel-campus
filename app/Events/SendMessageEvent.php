<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $data;
    public int $sender_id;
    public int $receiver_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $data, int $sender_id, int $receiver_id)
    {
        $this->data = $data;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['campus-message'];
    }

    public function broadcastAs()
    {
        return 'message-' . $this->receiver_id . '-' . $this->sender_id;
    }
}
