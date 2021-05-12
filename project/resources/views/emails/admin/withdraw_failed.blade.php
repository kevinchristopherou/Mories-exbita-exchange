@component('mail::message')
# {{ __('emailsadmin.subjects.withdraw_failed', ['app_name' => config('app.name')]) }}

{!! __('emailsadmin.withdraw_failed_'.$reason) !!}

<b>User:</b> {{ $withdraw->user->email }}<br>
<b>Amount:</b> {{ $withdraw->amount_decimal}} {{$withdraw->currency->symbol}}<br>
<b>Recepient:</b> {{ $withdraw->address}} <br>
<b>Withdraw Time:</b> {{ $withdraw->created_at }}<br>
@if($message)
<b>Error Message:</b> {{ $message }}<br>
@endif

{!! __('emails.best_regards', ['app_name' => config('app.name')]) !!}
@endcomponent