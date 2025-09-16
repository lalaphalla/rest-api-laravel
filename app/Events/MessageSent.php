<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct($user, $message)
    {
        Log::info('🟡 MessageSent Constructor called');
        $this->user = $user;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        Log::info('🔵 broadcastOn() called - returning chat channel');
        return [new Channel('chat')];
    }

    public function broadcastWith(): array
    {
        Log::info('🟢 broadcastWith() called - preparing data');

        $data = [
            'user' => is_object($this->user) ? $this->user->name : $this->user,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString(),
        ];

        Log::info('📤 Broadcasting data:', $data);
        return $data;
    }

    public function broadcastAs()
    {
        Log::info('🟣 broadcastAs() called - returning MessageSent');
        return 'MessageSent';
    }
}