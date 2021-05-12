@component('mail::message')
# {{ __('emails.password_changed_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.password_changed_text') !!}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent