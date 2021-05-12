<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Mail;

class AdminMailer
{
    private $mail;

    private $roles;

    public function __construct($mail, $roles = null)
    {
        $this->mail = $mail;
        $this->roles = $roles;
    }

    public function send()
    {
        $roles = $this->roles;
        $users = User::whereHas('roles', function ($query) use ($roles) {
            return $query->whereIn('slug', $roles);
        })->get();

        foreach ($users as $user) {
            Mail::to($user)->send($this->mail);
        }
    }
}
