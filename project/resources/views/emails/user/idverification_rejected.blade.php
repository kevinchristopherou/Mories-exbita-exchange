@component('mail::message')
# {{ __('emails.idverification_rejected_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.idverification_rejected', ['app_name' => config('app.name')]) !!}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent