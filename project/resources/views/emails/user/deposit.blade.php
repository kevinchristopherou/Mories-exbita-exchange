@component('mail::message')
# {{ __('emails.deposit_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.deposit_text', ['user' => $user->name, 'amount' => $amount, 'currency' => $currency]) !!}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent