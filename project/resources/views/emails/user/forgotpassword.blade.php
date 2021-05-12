@component('mail::message')
# {{ __('emails.forgot_password_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.forgot_password_follow_link') !!}

@component('mail::button', ['url' => $url])
    {!! __('emails.forgot_password_button_text') !!}
@endcomponent

{!! __('emails.forgot_password_copy_url') !!}

{{$url}}

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent