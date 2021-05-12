<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UserNewIpAllowEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $allowedIp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $allowedIp)
    {
        $this->user = $user;
        $this->allowedIp = $allowedIp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->user->locale);
        $url = config('app.url').'/#/ipverify/'.$this->allowedIp->id.'/'.$this->allowedIp->verify_token;

        return $this->markdown('emails.user.newipallow')
            ->subject(__('emails.ip_verification_subject', ['app_name' => config('app.name')]))
            ->with('url', $url)
            ->with('ip', $this->allowedIp);
    }
}
