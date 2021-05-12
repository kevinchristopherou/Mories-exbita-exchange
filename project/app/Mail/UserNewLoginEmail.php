<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UserNewLoginEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $ip;

    public $agent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->ip = $request->ip();
        $this->agent = $request->userAgent();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->user->locale);

        return $this->markdown('emails.user.newlogin')
            ->subject(__('emails.new_login_subject', ['app_name' => config('app.name')]))
            ->with('agent', $this->agent)
            ->with('time', Carbon::now()->toDateTimeString().' UTC')
            ->with('ip', $this->ip)
            ->with('url', $this->user->getDeactivationUrl());
    }
}
