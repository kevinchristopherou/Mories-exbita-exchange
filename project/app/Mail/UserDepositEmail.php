<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UserDepositEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $amount;

    public $currency;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->user = $data['user'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->user->locale);

        return $this->markdown('emails.user.deposit')
            ->subject(__('emails.deposit_subject', ['app_name' => config('app.name')]))
            ->with('user', $this->user)
            ->with('amount', $this->amount)
            ->with('currency', $this->currency);
    }
}
