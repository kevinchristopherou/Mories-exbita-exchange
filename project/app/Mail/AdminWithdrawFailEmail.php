<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminWithdrawFailEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $withdraw;

    public $reason;

    public $locale;

    public $message = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($withdraw, $reason)
    {
        $this->withdraw = $withdraw;
        $this->reason = $reason;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin.withdraw_failed')
            ->subject(__('emailsadmin.subjects.withdraw_failed', ['app_name' => config('app.name')]))
            ->with('withdraw', $this->withdraw)
            ->with('reason', $this->reason)
            ->with('message', $this->message);
    }
}
