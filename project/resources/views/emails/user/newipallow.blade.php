@component('mail::message')
# {{ __('emails.ip_verification_subject', ['app_name' => config('app.name')]) }}

{!! __('emails.new_ip_allow_text') !!}

@component('mail::button', ['url' => $url])
    {!! __('emails.new_ip_allow_button') !!}: {{$allowedIp->ip}}
@endcomponent

{!! __('emails.new_ip_allow_copy_url') !!}

{{$url}}

{!! __('emails.new_ip_allow_info', ['ip_address' => $allowedIp->ip]) !!}


{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent