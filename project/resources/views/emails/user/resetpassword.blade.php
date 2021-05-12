@component('mail::message')
# {{ __('emails.password_reset_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.reset_password_text') !!}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent