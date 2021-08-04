<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Themosis\Core\Bus\Dispatchable;

class NewMessageEvent implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;


//    public function __construct(Message $message, User $user) {
//        $this->message = $message;
//        $this->user = $user;
//    }
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn() {
//        return new PrivateChannel('chat');
        return new Channel('chat');
    }
    public function handle()
    {
        return 123;
        // Process uploaded podcast...
    }
//    public function broadcastWith() {
//        return [
//            'message' => $this->message,
//            'user' => $this->user,
//            'view' => view('site.personal.tabs.messenger.partials.messages_event', [
//                'message' => $this->message,
//                'user' => $this->user,
//            ])->render(),
//        ];
//    }

}
