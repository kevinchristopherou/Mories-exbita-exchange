@component('mail::message')
# {{ __('emails.new_login_subject', ['app_name' => config('app.name')]) }}


{!! __('emails.new_login_text') !!}

<b>{!! __('emails.new_login_time') !!}:</b> {{$time}}<br>
<b>{!! __('emails.new_login_ip_address') !!}:</b> {{$ip}}<br>
<b>{!! __('emails.new_login_agent') !!}:</b> {{$agent}}


{!! __('emails.new_login_warning') !!}

@component('mail::button', ['url' => $url])
    {!! __('emails.new_login_deactivate_my_account') !!}
@endcomponent

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent