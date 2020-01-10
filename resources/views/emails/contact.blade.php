@component('mail::message')
# Hello, {{ $adminname }}
{{ $message }}

<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->

Thanks,<br>
{{ $clientname }}<br>
{{ $phone }}<br>
Sent from {{ $mailfrom }}
<!-- {{ config('app.name') }} -->
@endcomponent
