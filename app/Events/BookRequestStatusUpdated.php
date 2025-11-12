<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\BookRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRequestStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookRequest;
    public $status;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param BookRequest $bookRequest
     * @param string $status
     * @param int $userId
     * @return void
     */
    public function __construct(BookRequest $bookRequest, string $status, int $userId)
    {
        $this->bookRequest = $bookRequest;
        $this->status = $status;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
{
    return new Channel('book-request.' . $this->bookRequest->student_id);
}
    
    public function broadcastAs()
    {
        return 'book.request.updated';
    }
}
