@component('mail::message')
# {{ __('emails.user_verification_subject', ['app_name' => config('app.name')]) }}

{{ __('emails.verification_welcome', ['app_name' => config('app.name')]) }}

{!! __('emails.verification_follow_link') !!}

@component('mail::button', ['url' => $url])
    {!! __('emails.verification_button') !!}
@endcomponent

{!! __('emails.verification_copy_url') !!}

{{$url}}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent