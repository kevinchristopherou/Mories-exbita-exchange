@component('mail::message')
# {{ __('emails.idverification_approved_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.idverification_approved', ['app_name' => config('app.name')]) !!}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent