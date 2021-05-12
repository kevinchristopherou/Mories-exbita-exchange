<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when frontend compiler finishes its job
 *
 */

class BuildFrontendCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $msg;

    public function __construct($type)
    {
        $this->msg = "Frontend build completed for $type is completed. ";
        if ($type === 'preview') {
            $this->msg .= 'You can check your changes from PREVIEW SITE</a>';
        }

        if ($type === 'production') {
            $this->msg .= 'You can check your changes from PRODUCTION SITE</a>';
        }
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin');
    }
}
