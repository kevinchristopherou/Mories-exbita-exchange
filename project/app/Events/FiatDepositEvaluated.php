<?php

namespace App\Events;

use App\Models\FiatDeposit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the deposit was moderated
 *
 */

class FiatDepositEvaluated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fiatDeposit;

    private $user_id;

    public function __construct(FiatDeposit $fiatDeposit)
    {
        $this->fiatDeposit = [
            'status' => $fiatDeposit->status,
            'amount' => $fiatDeposit->amount,
            'currency' => $fiatDeposit->fiatCurrency->symbol,
            'refcode' => $fiatDeposit->description,
            'note' => $fiatDeposit->note,
        ];

        $this->user_id = $fiatDeposit->user_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->user_id);
    }
}
