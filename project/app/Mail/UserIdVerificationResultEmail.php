<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UserIdVerificationResultEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->user->locale);

        if ($this->status === 0) {
            return $this->markdown('emails.user.idverification_rejected')
                ->subject(__('emails.idverification_rejected_subject', ['app_name' => config('app.name')]));
        } else {
            return $this->markdown('emails.user.idverification_approved')
                ->subject(__('emails.idverification_approved_subject', ['app_name' => config('app.name')]));
        }
    }
}
